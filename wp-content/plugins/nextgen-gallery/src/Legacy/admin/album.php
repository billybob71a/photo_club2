<?php

// Exit if accessed directly.
use Imagely\NGG\DataMappers\Album as AlbumMapper;
use Imagely\NGG\DataMappers\Gallery as GalleryMapper;

use Imagely\NGG\DataMappers\Image as ImageMapper;
use Imagely\NGG\DataTypes\Album;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class nggManageAlbum {

	/**
	 * The selected album ID
	 *
	 * @since 1.3.0
	 * @access privat
	 * @var int
	 */
	public $currentID = 0;

	/**
	 * The array for the galleries
	 *
	 * @since 1.3.0
	 * @access privat
	 * @var array
	 */
	public $galleries = false;

	/**
	 * The array for the albums
	 *
	 * @since 1.3.0
	 * @access privat
	 * @var array
	 */
	public $albums = [];

	/**
	 * The amount of all galleries
	 *
	 * @since 1.4.0
	 * @access privat
	 * @var int
	 */
	public $num_galleries = false;

	/**
	 * The amount of all albums
	 *
	 * @since 1.4.0
	 * @access privat
	 * @var int
	 */
	public $num_albums = false;

	/**
	 * Define allowed HTML tags and attributes.
	 *
	 * @since 3.59.6
	 * @access public
	 * @var array
	 */
	public $allowed_html_tags = [
		'a'      => [
			'href'   => [],
			'title'  => [],
			'target' => [],
		],
		'b'      => [],
		'i'      => [],
		'u'      => [],
		'em'     => [],
		'strong' => [],
		'p'      => [],
		'br'     => [],
		'ul'     => [],
		'ol'     => [],
		'li'     => [],
	];

	/**
	 * Gets the Pope component registry
	 *
	 * @return C_Component_Registry
	 */
	public function get_registry() {
		return \C_Component_Registry::get_instance();
	}

	/**
	 * Init the album output
	 */
	public function __construct() {
		return true;
	}

	public function controller() {

		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ngg_album' ) && isset( $_POST['update'] ) || isset( $_POST['delete'] ) || isset( $_POST['add'] ) ) {
			$this->processor();
		}

		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ngg_edit_album' ) && isset( $_POST['update_album'] ) ) {
			$this->update_album();
		}

		// get first all galleries & albums.
		$this->albums = [];
		foreach ( AlbumMapper::get_instance()->find_all() as $album ) {
			$this->albums[ $album->{$album->id_field} ] = $album;
		}

		$this->galleries = [];
		foreach ( GalleryMapper::get_instance()->find_all() as $gallery ) {
			$this->galleries[ $gallery->{$gallery->id_field} ] = $gallery;
		}

		if ( apply_filters( 'ngg_manage_albums_items_order', 'ASC' ) == 'DESC' ) {
			$this->albums    = array_reverse( $this->albums, true );
			$this->galleries = array_reverse( $this->galleries, true );
		}

		$this->num_albums    = count( $this->albums );
		$this->num_galleries = count( $this->galleries );

		$this->output();
	}

	public function _get_album( $id ) {
		$retval = null;

		if ( isset( $this->albums[ $id ] ) ) {
			$retval = $this->albums[ $id ];
		} else {
			$retval = AlbumMapper::get_instance()->find( $id );
		}

		return $retval;
	}

	public function _get_gallery( $id ) {
		$retval = null;

		if ( isset( $this->galleries[ $id ] ) ) {
			$retval = $this->galleries[ $id ];
		} else {
			$retval = GalleryMapper::get_instance()->find( $id );
		}

		return $retval;
	}

	/**
	 * Finds a suitable preview pic for the album if one hasn't been set already
	 *
	 * @param Album $album
	 * @return Album
	 */
	public function _set_album_preview_pic( $album ) {
		if ( ! isset( $album->sortorder ) || ! is_array( $album->sortorder ) ) {
			return $album;
		}

		$sortorder = array_merge( $album->sortorder );

		while ( ! $album->previewpic ) {
			// If the album is missing a preview pic, set one.
			if ( ( $first_entity = array_pop( $sortorder ) ) ) {

				// Is the first entity a gallery or album.
				if ( substr( $first_entity, 0, 1 ) == 'a' ) {
					$subalbum = $this->_get_album( substr( $first_entity, 1 ) );
					if ( $subalbum->previewpic ) {
						$album->previewpic = $subalbum->previewpic;
					}
				} else {
					$gallery = $this->_get_gallery( $first_entity );
					if ( $gallery && $gallery->previewpic ) {
						$album->previewpic = $gallery->previewpic;
					}
				}
			} else {
				break;
			}
		}

		return $album;
	}

	public function processor() {

		check_admin_referer( 'ngg_album' );

		// Create album.
		if ( isset( $_POST['add'] ) && isset( $_POST['newalbum'] ) ) {

			// Allow filtering of allowed HTML tags to extend custom tags and attributes.
			$allowed_html_tags = apply_filters( 'ngg_title_desc_custom_allowed_html_tags', $this->allowed_html_tags );

			// sanitize the album name.
			$name = wp_kses( $_POST['newalbum'], $allowed_html_tags );
			$name = stripslashes( $name );

			if( empty( $name ) ) {
				nggGallery::show_message( __( 'Album name is invalid', 'nggallery' ) );
				return;
			}

			if ( ! nggGallery::current_user_can( 'NextGEN Add/Delete album' ) ) {
				wp_die( esc_html__( 'Cheatin&#8217; uh?', 'nggallery' ) );
			}

			$album       = new stdClass();
			$album->name = $name;
			if ( AlbumMapper::get_instance()->save( $album ) ) {
				$this->currentID = $_REQUEST['act_album'] = $album->{$album->id_field};

				$this->albums[ $this->currentID ] = $album;

				do_action( 'ngg_add_album', $this->currentID );
				wp_safe_redirect( admin_url( 'admin.php?page=nggallery-manage-album&act_album=' . $album->{$album->id_field} ) );
			} else {
				$this->currentID = $_REQUEST['act_album'] = 0;
			}
		} elseif ( isset( $_POST['update'] ) && isset( $_REQUEST['act_album'] ) && $this->currentID = intval( $_REQUEST['act_album'] ) ) {

			$sortorder = [];

			// Get the current album being updated.
			$album = $this->_get_album( $this->currentID );

			// Get the list of galleries/sub-albums to be added to this album.
			parse_str( $_REQUEST['sortorder'], $sortorder );

			// Set the new sortorder.
			if ( isset( $sortorder['gid'] ) ) {
				$album->sortorder = $sortorder['gid'];
			} else {
				$album->sortorder = [];
			}

			// Ensure that a preview pic has been sent.
			$this->_set_album_preview_pic( $album );

			// Save the changes.
			AlbumMapper::get_instance()->save( $album );

			// hook for other plugins.
			do_action( 'ngg_update_album_sortorder', $this->currentID );

			nggGallery::show_message( __( 'Updated Successfully', 'nggallery' ) );

		}

		if ( isset( $_POST['delete'] ) ) {

			if ( ! nggGallery::current_user_can( 'NextGEN Add/Delete album' ) ) {
				wp_die( esc_html__( 'Cheatin&#8217; uh?', 'nggallery' ) );
			}

			$this->currentID = (int) $_REQUEST['act_album'];

			if ( AlbumMapper::get_instance()->destroy( $this->currentID ) ) {
				// hook for other plugins.
				do_action( 'ngg_delete_album', $this->currentID );

				// jump back to main selection.
				$this->currentID = $_REQUEST['act_album'] = 0;

				nggGallery::show_message( __( 'Album deleted', 'nggallery' ) );
			}
		}
	}

	public function update_album() {
		check_admin_referer( 'ngg_edit_album' );

		if ( ! nggGallery::current_user_can( 'NextGEN Edit album settings' ) ) {
			wp_die( esc_html__( 'Cheatin&#8217; uh?', 'nggallery' ) );
		}

		// Allow filtering of allowed HTML tags to extend custom tags and attributes.
		$allowed_html_tags = apply_filters( 'ngg_title_desc_custom_allowed_html_tags', $this->allowed_html_tags );

		// Sanitize the album name.
		$album_name = wp_kses( $_POST['album_name'], $allowed_html_tags );
		$album_name = stripslashes( $album_name );

		// Sanitize the album description.
		$album_desc = wp_kses( $_POST['album_desc'], $allowed_html_tags );
		$album_desc = stripslashes( $album_desc );

		$this->currentID   = $_REQUEST['act_album'];
		$album             = $this->_get_album( $this->currentID );
		$album->name       = $album_name;
		$album->albumdesc  = $album_desc;
		$album->previewpic = (int) $_POST['previewpic'];
		$album->pageid     = (int) $_POST['pageid'];
		$result            = AlbumMapper::get_instance()->save( $album );

		// hook for other plugin to update the fields.
		do_action( 'ngg_update_album', $this->currentID, $_POST );

		if ( $result ) {
			nggGallery::show_message( __( 'Updated Successfully', 'nggallery' ) );
		}
	}

	public function get_available_preview_images( $album ) {
		$retval = [];

		if ( $album && isset( $album->sortorder ) && $album->sortorder ) {
			$galleries = [];
			$albums    = [];
			foreach ( $album->sortorder as $item ) {
				if ( is_numeric( $item ) ) {
					$galleries[] = $item;
				} else {
					$albums[] = $item;
				}
			}

			$image_mapper = ImageMapper::get_instance();
			$retval      += $image_mapper->select( 'DISTINCT *' )->where( [ 'galleryid IN %s', $galleries ] )->where( 'exclude != 1' )->run_query();
			foreach ( $albums as $subalbum ) {
				$retval += $this->get_available_preview_images( $subalbum );
			}
		} else {
			// enforce a reasonable limit on how many images to offer.
			$retval = ImageMapper::get_instance()
								->select()
								->where_and( [] )
								->limit( intval( \Imagely\NGG\Settings\Settings::get_instance()->get( 'maximum_entity_count', 500 ) ) )
								->run_query();
		}

		return $retval;
	}

	public function output() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['act_album'] ) ) {
			// Nonce verification is not necessary here: we are only determining which album to view, and it's ID is
			// always cast to an integer.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->currentID = intval( $_REQUEST['act_album'] );
		}

		$album = $this->_get_album( $this->currentID );

		// Generate JSON for autocomplete of preview images.
		$preview_images = $this->get_available_preview_images( $album );

		// TODO:Code MUST be optimized, how to flag a used gallery better ?
		$used_list = $this->get_used_galleries();
		?>
<style type="text/css">
	.select2-container { z-index: 10000; }
	.select2-container {max-width: 580px; }
	.select2-drop { max-width: 580px; }
	.select2-choice { max-width: 580px;}
</style>

<script type="text/javascript">
(function($) {
	$(function() {
		if ($(this).data('ready')) {
			return;
		}

		if (window.Frame_Event_Publisher) {
			// Refresh when a new gallery has been added
			Frame_Event_Publisher.listen_for('attach_to_post:manage_galleries attach_to_post:new_gallery', function () {
				window.location.href = window.location.href.toString();
			});

			// Updates the thumbnail image when a previewpic has been modified
			Frame_Event_Publisher.listen_for('attach_to_post:thumbnail_modified', function (data) {
				var image_id = data.image[data.image.id_field];
				var $image = $('img[rel="' + image_id + '"]');
				if ($image.length > 0) {
					$image.attr('src', data.image.thumb_url);
				}
			});
		}

		// Select2 doesn't play nicely inside of jQuery-UI modals; this following block
		// is necessary to allow the select2 search field to receive input focus
		if ($.ui && $.ui.dialog && $.ui.dialog.prototype._allowInteraction) {
			var allowInteraction = $.ui.dialog.prototype._allowInteraction;
			$.ui.dialog.prototype._allowInteraction = function (e) {
				if ($(e.target).closest('.select2-dropdown').length) {
					return true;
				}
				return allowInteraction.apply(this, arguments);
			};
		}

		$("#previewpic").select2({
			width: '100%'
		});

		jQuery('#selectContainer').sortable({
			items: '.groupItem',
			placeholder: 'sort_placeholder',
			opacity: 0.7,
			tolerance: 'intersect',
			distance: 2,
			forcePlaceholderSize: true,
			connectWith: ['#galleryContainer']
		});

		jQuery('#galleryContainer').sortable({
			items: '.groupItem',
			placeholder: 'sort_placeholder',
			opacity: 0.7,
			tolerance: 'intersect',
			distance: 2,
			forcePlaceholderSize: true,
			connectWith: ['#selectContainer', '#albumContainer']
		});

		jQuery('#albumContainer').sortable({
			items: '.groupItem',
			placeholder: 'sort_placeholder',
			opacity: 0.7,
			tolerance: 'intersect',
			distance: 2,
			forcePlaceholderSize: true,
			connectWith: ['#galleryContainer']
		});

		jQuery('a.min').on('click', toggleContent);

		// Hide used galleries
		jQuery('a#toggle_used').on('click', function () {
			jQuery('#selectContainer div.inUse').toggle();
			return false;
		});

		// Maximize All Portlets (whole site, no differentiation)
		jQuery('a#all_max').on('click', function () {
			jQuery('div.itemContent:hidden').show();
			return false;
		});

		// Minimize All Portlets (whole site, no differentiation)
		jQuery('a#all_min').on('click', function () {
			jQuery('div.itemContent:visible').hide();
			return false;
		});

		// Auto Minimize if more than 4 (whole site, no differentiation)
		if (jQuery('a.min').length > 4) {
			jQuery('a.min').html('[+]');
			jQuery('div.itemContent:visible').hide();
			jQuery('#selectContainer div.inUse').toggle();
		}

		$(this).data('ready', true);
	});
})(jQuery);

var toggleContent = function(e) {
	var targetContent = jQuery('div.itemContent', this.parentNode.parentNode);
	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(300);
		jQuery(this).html('[-]');
	} else {
		targetContent.slideUp(300);
		jQuery(this).html('[+]');
	}
	return false;
};

function ngg_serialize(s) {
	//serial = jQuery.SortSerialize(s);
	serial = jQuery('#galleryContainer').sortable('serialize');
	jQuery('input[name=sortorder]').val(serial);
	return serial;
}

function showDialog() {
	jQuery( "#editalbum").dialog({
		width: 640,
		resizable : false,
		modal: true,
		title: '<?php echo esc_js( __( 'Edit Album', 'nggallery' ) ); ?>',
		position: {
			my:		'center',
			at:		'center',
			of:		window.parent
		}
	});
	jQuery('#editalbum .dialog-cancel').on('click', function() { jQuery( "#editalbum" ).dialog("close"); });
	jQuery('.ui-dialog-titlebar-close').text('X')
}

// Redirect to edit the chosen album when the ngg_select_album field changes
function ngg_redirect_to_album(album_field) {
	var this_page_url = '<?php print admin_url( 'admin.php?page=nggallery-manage-album' ); ?>';
	var value = jQuery(album_field).val();
	if (value !== 0 && value !== '0') {
		this_page_url += '&act_album=' + value;
	}
	window.location = this_page_url;
}

function ngg_confirm_delete_album(form) {
	var check = confirm('<?php echo esc_js( 'Delete album ?', 'nggallery' ); ?>');
	if (check) {
		jQuery(form).attr('action', '<?php print admin_url( 'admin.php?page=nggallery-manage-album' ); ?>');
	}
	return check;
}

</script>

<div class="wrap album ngg_manage_albums" id="wrap" >

	<div class="ngg_page_content_header">
		<h3><?php esc_html_e( 'Manage Albums', 'nggallery' ); ?></h3>
	</div>

	<div class='ngg_page_content_main'>
		<form id="selectalbum" method="POST" onsubmit="ngg_serialize()" accept-charset="utf-8">
			<?php wp_nonce_field( 'ngg_album' ); ?>
			<input name="sortorder" type="hidden" />
			<div class="albumnav tablenav">
				<div class="alignleft actions">
					<span class="ngg_select_album"><?php esc_html_e( 'Select album', 'nggallery' ); ?></span>
					<select id="act_album" name="act_album" onchange="ngg_redirect_to_album(this)">
						<option value="0" ><?php esc_html_e( 'No album selected', 'nggallery' ); ?></option>
						<?php
						if ( is_array( $this->albums ) ) {
							foreach ( $this->albums as $a ) {
								$selected = ( $this->currentID == $a->id ) ? 'selected="selected" ' : '';
								echo '<option value="' . esc_attr( $a->id ) . '" ' . $selected . '>' . esc_html( $a->id . ' - ' . wp_strip_all_tags( $a->name ) ) . '</option>' . "\n";
							}
						}
						?>
					</select>
					<?php if ( $album && $this->currentID ) { ?>
						<input class="button-primary"
								type="submit"
								name="update"
								value="<?php esc_attr_e( 'Update', 'nggallery' ); ?>"/>
						<?php if ( nggGallery::current_user_can( 'NextGEN Edit album settings' ) ) { ?>
							<input class="button-primary"
									type="submit"
									name="showThickbox"
									value="<?php esc_attr_e( 'Edit Album', 'nggallery' ); ?>"
									onclick="showDialog(); return false;"/>
						<?php } ?>
						<?php if ( nggGallery::current_user_can( 'NextGEN Add/Delete album' ) ) { ?>
							<input class="button-primary action"
									type="submit"
									name="delete"
									value="<?php esc_attr_e( 'Delete', 'nggallery' ); ?>"
									onclick="ngg_confirm_delete_album(this.form);"/>
						<?php } ?>
					<?php } else { ?>
						<?php if ( nggGallery::current_user_can( 'NextGEN Add/Delete album' ) ) { ?>
							<span class="ngg_new_album"><?php esc_html_e( 'Add new album', 'nggallery' ); ?>&nbsp;</span>
							<input class="search-input" id="newalbum" name="newalbum" type="text" value="" required />
							<input class="button-primary action" type="submit" name="add" value="<?php esc_attr_e( 'Add', 'nggallery' ); ?>"/>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</form>

		<br class="clear"/>

		<div>
			<div style="float:right;">
				<a href="#" title="<?php esc_attr_e( 'Show / hide used galleries', 'nggallery' ); ?>" id="toggle_used"><?php esc_html_e( 'Show all', 'nggallery' ); ?></a>
			| <a href="#" title="<?php esc_attr_e( 'Maximize the widget content', 'nggallery' ); ?>" id="all_max"><?php esc_html_e( 'Maximize', 'nggallery' ); ?></a>
			| <a href="#" title="<?php esc_attr_e( 'Minimize the widget content', 'nggallery' ); ?>" id="all_min"><?php esc_html_e( 'Minimize', 'nggallery' ); ?></a>
			</div>
			<?php esc_html_e( 'After you create and select an album, you can drag and drop a gallery or another album into your new album below.', 'nggallery' ); ?>
		</div>

		<br class="clear" />

		<div class="container">

			<!-- /#target-album -->
			<div class="widget target-album widget-left">
			<?php if ( $album && $this->currentID ) { ?>
					<div class="widget-top">
						<h3><?php esc_html_e( 'Album ID', 'nggallery' ); ?> <?php echo $album->id . ' : ' . esc_html( wp_strip_all_tags( $album->name ) ); ?> </h3>
					</div>
					<div id="galleryContainer" class="widget-holder target">
					<?php
					$sort_array = $album->sortorder;
					foreach ( $sort_array as $galleryid ) {
						$this->get_container( $galleryid, false );
					}
			} else {
				?>
					<div class="widget-top">
						<h3><?php esc_html_e( 'No album selected!', 'nggallery' ); ?></h3>
					</div>
					<div class="widget-holder target">
					<?php
			}
			?>
				</div>
			</div><!-- /#target-album -->

			<!-- /#select container -->
			<div class="widget widget-right">
				<div class="widget-top">
					<h3><?php esc_html_e( 'Select gallery', 'nggallery' ); ?></h3>
				</div>
				<div id="selectContainer" class="widget-holder">
					<?php
					if ( is_array( $this->galleries ) ) {
						// get the array of galleries.
						$sort_array = $album ? $album->sortorder : [];
						foreach ( $this->galleries as $gallery ) {
							if ( ! in_array( $gallery->gid, $sort_array ) ) {
								if ( in_array( $gallery->gid, $used_list ) ) {
									$this->get_container( $gallery->gid, true );
								} else {
									$this->get_container( $gallery->gid, false );
								}
							}
						}
					}
					?>
				</div>
			</div>

			<!-- /#album container -->
			<div class="widget widget-right">
				<div class="widget-top">
					<h3><?php esc_html_e( 'Select album', 'nggallery' ); ?></h3>
				</div>
				<div id="albumContainer" class="widget-holder">
					<?php
					if ( is_array( $this->albums ) ) {
						foreach ( $this->albums as $a ) {
							$this->get_container( 'a' . $a->id );
						}
					}
					?>
				</div>
			</div>

		</div><!-- /#container -->

		<?php do_action( 'ngg_manage_albums_marketing_block' ); ?>

	</div> <!-- /.ngg_page_content_main -->

</div><!-- /#wrap -->

		<?php if ( $album && $this->currentID ) : ?>
<!-- #editalbum -->
<div id="editalbum" style="display: none;" >
	<form id="form-edit-album" method="POST" accept-charset="utf-8">
			<?php wp_nonce_field( 'ngg_edit_album' ); ?>
	<input type="hidden" id="current_album" name="act_album" value="<?php print esc_attr( $this->currentID ); ?>" />
	<table width="100%" border="0" cellspacing="3" cellpadding="3" >
			<tr>
			<th>
						<?php esc_html_e( 'Album name:', 'nggallery' ); ?><br />
				<input class="search-input" id="album_name" name="album_name" type="text" value="<?php echo esc_attr( $album->name ); ?>" style="width:95%" />
			</th>
			</tr>
			<tr>
			<th>
						<?php esc_html_e( 'Album description:', 'nggallery' ); ?><br />
				<textarea class="search-input" id="album_desc" name="album_desc" cols="50" rows="2" style="width:95%" ><?php echo esc_attr( $album->albumdesc ); ?></textarea>
			</th>
			</tr>
			<tr>
			<th>
						<?php esc_html_e( 'Select a preview image:', 'nggallery' ); ?><br />
					<select id="previewpic" name="previewpic" data-placeholder="<?php esc_attr_e( 'No picture', 'nggallery' ); ?>">
						<?php foreach ( $preview_images as $image ) : ?>
							<option value="<?php echo esc_attr( $image->pid ); ?>" <?php selected( $album->previewpic, $image->pid ); ?>>
								<?php $label = $image->alttext ? $image->alttext : $image->filename; ?>
								<?php echo esc_html( $image->pid ); ?> - <?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach ?>
					</select>
			</th>
			</tr>
		<tr>
			<th>
						<?php esc_html_e( 'Page Link to', 'nggallery' ); ?><br />
						<?php
						if ( ! isset( $album->pageid ) ) {
							$album->pageid = 0;
						}

						ob_start();
						wp_dropdown_pages(
							[
								'echo'              => true,
								'name'              => 'pageid',
								'selected'          => (int) $album->pageid,
								'show_option_none'  => esc_html__( 'Not linked', 'nggallery' ),
								'option_none_value' => 0,
							]
						);
						$dropdown = ob_get_contents();
						ob_end_clean();
						if ( ! empty( $dropdown ) ) {
							echo $dropdown;
						} else {
							echo '<input type="hidden" id="pageid" name="pageid" value="0"/>';
							esc_html_e( 'There are no pages to link to', 'nggallery' );
						}
						?>
			</th>
		</tr>

				<?php do_action( 'ngg_edit_album_settings', $this->currentID ); ?>

			<tr align="right">
			<td class="submit">
				<input type="submit" class="button-primary" name="update_album" value="<?php esc_attr_e( 'OK', 'nggallery' ); ?>" />
				&nbsp;
				<input class="button-primary dialog-cancel" type="reset" value="<?php esc_attr_e( 'Cancel', 'nggallery' ); ?>"/>
			</td>
		</tr>
	</table>
	</form>
</div>
<!-- /#editalbum -->
		<?php endif; ?>

		<?php
	}

	/**
	 * Create the album or gallery container
	 *
	 * @param integer $id (the prefix 'a' indidcates that you look for a album
	 * @param bool    $used (object will be hidden)
	 * @return null|string
	 */
	public function get_container( $id = 0, $used = false ) {
		global $nggdb;

		$retval        = null;
		$obj           = [];
		$preview_image = '';
		$class         = '';

		// if the id started with a 'a', then it's a sub album.
		if ( substr( $id, 0, 1 ) == 'a' ) {

			if ( ! $album = $this->_get_album( substr( $id, 1 ) ) ) {
				return $retval;
			}

			$obj['id']   = $album->id;
			$obj['name'] = $obj['title'] = $album->name;
			$obj['type'] = 'album';
			$class       = 'album_obj';

			// get the post name.
			$post             = get_post( $album->pageid );
			$obj['pagenname'] = ( $post == null ) ? '---' : $post->post_title;

			// for speed reason we limit it to 50.
			if ( $this->num_albums < 50 ) {
				$thumbURL = '';
				if ( $album->previewpic ) {
					$image = $nggdb->find_image( $album->previewpic );
					if ( $image && $image->thumbURL ) {
						$thumbURL = @add_query_arg( 'timestamp', time(), $image->thumbURL );
					}
				}
				$preview_image = $thumbURL ? '<div class="inlinepicture"><img rel="' . $album->previewpic . '" src="' . \Imagely\NGG\Util\Router::esc_url( $thumbURL ) . '" /></div>' : '';
			}

			// this indicates that we have a album container.
			$prefix = 'a';

		} else {
			$mapper  = GalleryMapper::get_instance();
			$gallery = $mapper->find( $id );
			if ( ! $gallery ) {
				return $retval;
			}

			$obj['id']    = $gallery->gid;
			$obj['name']  = $gallery->name;
			$obj['title'] = $gallery->title;
			$obj['type']  = 'gallery';

			// get the post name.
			$post             = get_post( $gallery->pageid );
			$obj['pagenname'] = ( $post == null ) ? '---' : $post->post_title;

			// for spped reason we limit it to 50.
			if ( $this->num_galleries < 50 ) {
				// set image url.
				$thumbURL = '';
				if ( $gallery->previewpic ) {
					$image = $nggdb->find_image( $gallery->previewpic );
					if ( $image && $image->thumbURL ) {
						$thumbURL = @add_query_arg( 'timestamp', time(), $image->thumbURL );
					}
				}
				$preview_image = ( ! is_null( $thumbURL ) ) ? '<div class="inlinepicture"><img rel="' . $gallery->previewpic . '" src="' . \Imagely\NGG\Util\Router::esc_url( $thumbURL ) . '" /></div>' : '';
			}

			$prefix = '';
		}

		// add class if it's in use in other albums.
		$used = $used ? ' inUse' : '';

		$obj_name  = wp_strip_all_tags( $obj['name'] );
		$obj_title = wp_strip_all_tags( $obj['title'] );

		$retval = '<div id="gid-' . $prefix . $obj['id'] . '" class="groupItem' . $used . '">
				<div class="innerhandle">
					<div class="item_top ' . $class . '">
						<a href="#" class="min" title="close">[-]</a>
						ID: ' . $obj['id'] . ' | ' . wp_html_excerpt( esc_html( \Imagely\NGG\Display\I18N::translate( $obj_title ) ), 25 ) . '
					</div>
					<div class="itemContent">
							' . $preview_image . '
							<p><strong>' . __( 'Name', 'nggallery' ) . ' : </strong>' . esc_html( \Imagely\NGG\Display\I18N::translate( $obj_name ) ) . '</p>
							<p><strong>' . __( 'Title', 'nggallery' ) . ' : </strong>' . esc_html( \Imagely\NGG\Display\I18N::translate( $obj_title ) ) . '</p>
							<p><strong>' . __( 'Page', 'nggallery' ) . ' : </strong>' . esc_html( \Imagely\NGG\Display\I18N::translate( $obj['pagenname'] ) ) . '</p>
							' . apply_filters( 'ngg_display_album_item_content', '', $obj ) . '
						</div>
				</div>
			   	</div>';
				echo $retval;
				return $retval;
	}

	/**
	 * get all used galleries from all albums
	 *
	 * @return array $used_galleries_ids
	 */
	public function get_used_galleries() {

		$used = [];

		if ( $this->albums ) {
			foreach ( $this->albums as $album ) {
				if ( ! is_array( $album->sortorder ) ) {
					continue;
				}
				foreach ( $album->sortorder as $galleryid ) {
					if ( ! in_array( $galleryid, $used ) ) {
						$used[] = $galleryid;
					}
				}
			}
		}

		return $used;
	}

	/**
	 * PHP5 style destructor
	 *
	 * @return bool Always true
	 */
	public function __destruct() {
		return true;
	}
}
