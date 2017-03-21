<?php

/**
 * Class GridGallery_Photos_Model_Photo
 *
 * @package GridGallery\Photos\Model
 * @author Artur Kovalevsky
 */
class GridGallery_Photos_Model_Photos extends Rsc_Mvc_Model
{

    /**
     * @var bool
     */
    protected $debugEnabled;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var int
     */
    protected $insertId;

    /**
     * @var string
     */
    protected $lastError;

    /**
     * Constructor
     */
    public function __construct($debugEnabled = false)
    {
        parent::__construct();

        $this->debugEnabled = (bool)$debugEnabled;
        $this->table = $this->db->prefix . 'gg_photos';
    }

    /**
     * @param boolean $debugEnabled
     * @return GridGallery_Photos_Model_Photos
     */
    public function setDebugEnabled($debugEnabled)
    {
        $this->debugEnabled = $debugEnabled;
        return $this;
    }

    /**
     * Returns the identifier of the last inserted photo
     * @return int
     */
    public function getInsertId()
    {
        return $this->insertId;
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Adds the photo to the database.
     * @param int $attachmentId The identifier of the attachment to add
     * @param int $folderId The identifier of the folder (Default: 0)
     * @return bool
     */
    public function add($attachmentId, $folderId = 0)
    {
        $query = $this->getQueryBuilder()->insertInto($this->table)
            ->fields('attachment_id', 'folder_id')
            ->values((int)$attachmentId, (int)$folderId);

        if (!$this->db->query($query->build())) {
            $this->lastError = $this->db->last_error;
            return false;
        }

        $this->insertId = $this->db->insert_id;
        return true;
    }

    /**
     * Returns the photo by the id
     * @param int $id The identifier of the photo
     * @return object $photo or NULL on failure
     */
    public function getById($id)
    {
        return $this->getBy('id', (int)$id);
    }

    /**
     * Returns the photo by the attachment id
     * @param int $attachmentId The identifier of the attachment
     * @return object $photo or NULL on failure
     */
    public function getByAttachmentId($attachmentId)
    {
        $query = $this->getQueryBuilder()->select('*')
            ->from($this->table)
            ->where('attachment_id', '=', (int)$attachmentId)
            ->orderBy('id')
            ->order('DESC');

        if (null === $photo = $this->db->get_row($query->build(), ARRAY_A)) {
            return null;
        }

        return $this->extend($photo);
    }

    /**
     * Returns the array of the photos linked to the specified folder
     * @param int $folderId The identifier of the folder
     * @return array|null
     */
    public function getPhotosByFolderId($folderId)
    {
        $query = $this->getQueryBuilder()->select('*')
            ->from($this->table)
            ->where('folder_id', '=', (int)$folderId);

        if ($photos = $this->db->get_results($query->build())) {
            foreach ($photos as $index => $photo) {
                $photos[$index] = $this->extend($photo);
            }
        }

        return $photos;
    }

    /**
     * Returns the folder by the photo id
     * @param int $photoId The identifier of the photo
     * @return null|object
     */
    public function getFolderByPhotoId($photoId)
    {
        if (!$photo = $this->getById($photoId)) {
            return null;
        }

        if (!class_exists($classname = 'GridGallery_Photos_Model_Folders', false)) {
            if ($this->debugEnabled) {
                wp_die(sprintf('The required class \'%s\' is does not exists', $classname));
            }

            return null;
        }

        $folders = new GridGallery_Photos_Model_Folders($this->debugEnabled);

        return $folders->getById($photo->folder_id);
    }

    /**
     * Returns the array of the photos
     * @return array|null
     */
    public function getAll()
    {
        $query = $this->getQueryBuilder()->select('*')
            ->from($this->table);

        if ($photos = $this->db->get_results($query->build())) {
            foreach ($photos as $index => $photo) {
                $photos[$index] = $this->extend($photo);
            }
        }

        return $photos;
    }

    /**
     * Returns all photos without folders
     * @return array|null
     */
    public function getAllWithoutFolders()
    {
        $query = $this->getQueryBuilder()->select('*')
            ->from($this->table)
            ->where('folder_id', '=', 0);

        if ($photos = $this->db->get_results($query->build())) {
            foreach ($photos as $index => $photo) {
                $photos[$index] = $this->extend($photo);
            }
        }

        return $photos;
    }

    /**
     * Deletes photo from the plugin by the attachment id
     * @param int $attachmentId The identifier of the attachment
     * @return bool TRUE on success, FALSE otherwise
     */
    public function deleteByAttachmentId($attachmentId)
    {
        $attachmentId = (int)$attachmentId;
        $photo = $this->getByAttachmentId($attachmentId);

        do_action('gg_delete_photo_attachment_id', $attachmentId);

        return $this->deleteBy('attachment_id', $attachmentId);
    }

    /**
     * Deletes photo from the plugin by the identifier
     * @param int $id The identifier of the photo
     * @return bool TRUE of success, FALSE otherwise
     */
    public function deleteById($id)
    {
        do_action('gg_delete_photo_id', $id);

        return $this->deleteBy('id', (int)$id);
    }

    public function deleteByFolderId($id)
    {
        return $this->deleteBy('folder_id', $id);
    }

    /**
     * Moves selected photo to the selected folder
     * @param int $photoId The identifier of the photo
     * @param int|null $folderId The identifier of the folder
     * @return bool TRUE on success, FALSE otherwise
     */
    public function toFolder($photoId, $folderId = null)
    {
        if (!$this->getById((int)$photoId)) {
            return false;
        }

        $folders = new GridGallery_Photos_Model_Folders($this->debugEnabled);

        $query = $this->getQueryBuilder()->update($this->table)
            ->fields('folder_id')
            ->values(($folderId === null ? $folderId : (int)$folderId))
            ->where('id', '=', (int)$photoId);

        if (!$this->db->query($query->build())) {
            $this->lastError = $this->db->last_error;
            return false;
        }

        return true;
    }

    public function setAlt($attachmentId, $alt)
    {
        $alt = htmlspecialchars($alt, ENT_QUOTES, get_bloginfo('charset'));

        update_post_meta((int)$attachmentId, '_wp_attachment_image_alt', $alt);
    }

    public function setCaption($attachmentId, $caption)
    {
        $caption = htmlspecialchars(
            $caption,
            ENT_QUOTES,
            get_bloginfo('charset')
        );

        wp_update_post(
            array(
                'ID' => (int)$attachmentId,
                'post_excerpt' => $caption,
            )
        );
    }

    public function setDescription($attachmentId, $description)
    {
        $description = htmlspecialchars(
            $description,
            ENT_QUOTES,
            get_bloginfo('charset')
        );

        wp_update_post(
            array(
                'ID' => (int)$attachmentId,
                'post_content' => $description,
            )
        );
    }

    public function getMetadataAliases()
    {
        return array(
            'link'   => '_grid_gallery_link',
            'target' => '_grid_gallery_target'
        );
    }

    public function getMetadataField($alias)
    {
        $metadata = $this->getMetadataAliases();

        return (isset($metadata[$alias]) ? $metadata[$alias] : null);
    }

    public function setLink($attachmentId, $link)
    {
        update_post_meta(
            (int)$attachmentId,
            $this->getMetadataField('link'),
            $link
        );
    }

    public function setCaptionEffect($attachmentId, $captionEffect)
    {
        update_post_meta(
            (int)$attachmentId,
            'captionEffect',
            $captionEffect
        );
    }

    /**
     * Sets a target.
     * @param int    $attachmentId Attachment ID.
     * @param string $target       Target attribute (_self, _blank, etc).
     */
    public function setTarget($attachmentId, $target)
    {
        update_post_meta(
            (int)$attachmentId,
            $this->getMetadataField('target'),
            $target
        );
    }

    public function updateMetadata($attachmentId, array $metadata)
    {
        foreach ($metadata as $key => $value) {
            if (!method_exists($this, $method = sprintf('set%s', ucfirst($key)))) {
                throw new BadMethodCallException(
                    sprintf('The method %s does not exists.', $method)
                );
            }

            call_user_func_array(array($this, $method), array(
                (int)$attachmentId, $value
            ));
        }
    }

    /**
     * Returns the data of the photo by the specified field
     * @param string $field The name of the field
     * @param mixed $identifier The identifier
     * @return object $photo or NULL
     */
    protected function getBy($field, $identifier)
    {
        if ($this->debugEnabled) {

            $metadata = $this->db->get_results(sprintf('SHOW COLUMNS FROM %s', $this->table));
            $fields = array();

            foreach ($metadata as $column) {
                $fields[] = $column->Field;
            }

            if (!in_array($field, $fields)) {
                wp_die(sprintf('The field \'%s\' is does not exists in the table \'%s\'', $field, $this->table));
            }
        }

        $query = $this->getQueryBuilder()->select('*')
            ->from($this->table)
            ->where($field, '=', $identifier);

        if ($photo = $this->db->get_row($query->build())) {
            $photo = $this->extend($photo);
        }

        return $photo;
    }

    public function getPhotos($resourcesData)
    {
        $query = $this->getQueryBuilder()->select('*')
            ->from($this->table);
        $photos = array();

        //var_dump($resourcesData);
        if($photosList = $this->db->get_results($query->build())) {
            foreach($resourcesData as $data) {
                foreach($photosList as $element) {
                    if($element->id == $data->resource_id) {
                        array_push($photos, $this->extend($element));
                    }
                }
            }
        }

        return $photos;
    }

    /**
     * Deletes row(s) by specified field
     * @param string $field The name of the field
     * @param mixed $identifier The identifier
     * @return bool TRUE on success, FALSE otherwise
     */
    protected function deleteBy($field, $identifier)
    {
        if ($this->debugEnabled) {
            $metadata = $this->db->get_results(sprintf('SHOW COLUMNS FROM %s', $this->table));
            $fields = array();

            foreach ($metadata as $column) {
                $fields[] = $column->Field;
            }

            if (!in_array($field, $fields)) {
                wp_die(sprintf('The field \'%s\' is does not exists in the table \'%s\'', $field, $this->table));
            }
        }

        $query = $this->getQueryBuilder()->deleteFrom($this->table)
            ->where($field, '=', $identifier);

        if (!$this->db->query($query->build())) {
            return false;
        }

        return true;
    }

    public function getUsedTimes($photo)
    {
        $resources = new GridGallery_Galleries_Model_Resources();

        if ($photo->folder_id > 0) {
            $galleries = $resources->getGalleriesWithFolder($photo->folder_id);
        } else {
            $galleries = $resources->getGalleriesWithPhoto($photo->id);
        }

        return count($galleries);
    }

    /**
     * Extends the default database result for photo
     * @param object|array $photo The default database result for photo
     * @return object
     */
    public function extend($photo)
    {
        if (!is_object($photo) && !is_array($photo)) {
            if ($this->debugEnabled) {
                wp_die('Invalid $photo parameter specified');
            }
        }

        $photo = (object)$photo;

        $usedTimes = $this->getUsedTimes($photo);

        $photo->attachment = wp_prepare_attachment_for_js($photo->attachment_id);
        $photo->is_used = (($usedTimes > 0) ? true : false);
        $photo->used_times = $usedTimes;
        $captionEffect = get_post_meta($photo->attachment_id, 'captionEffect');
        if($captionEffect && !empty($captionEffect)) {
            $photo->attachment['captionEffect'] = $captionEffect[0];
        }

        if (class_exists('GridGalleryPro_Galleries_Model_Tags')) {
            $tags = new GridGalleryPro_Galleries_Model_Tags();
            $result = $tags->getByPhotoId($photo->id);
            $photo->tags = array();

            if (is_object($result) && property_exists($result, 'tags')) {
                $photo->tags = explode(',', $result->tags);
            }
        }

        return $photo;
    }
}
