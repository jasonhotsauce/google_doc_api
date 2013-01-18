<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class is an object of google document.
 * @author Wenbin Zhang
 *
 */
class Document {
    private $id;
    private $title;
    private $createdTime;
    private $updatedTime;
    private $shareLink;
    private $embedLink;
    private $editLink;
    private $deleteLink;
    private $downloadLink;
    private $etag;
    private $editable;
    private $icon;
    private $thumbnail;
    private $owners;
    private $exportLinks;
    private $trashed;
    private $viewed;
    private $restricted;
    
    public function __construct($dataArray = NULL) {
        if (!is_null($dataArray) && is_array($dataArray)) {
            $this->id = $dataArray['id'];
            $this->viewed = $dataArray['labels']['viewed'];
            $this->restricted = $dataArray['labels']['restricted'];
            if (key_exists('title', $dataArray))
                $this->title = $dataArray['title'];
            if (key_exists('alternateLink', $dataArray))
                $this->editLink = $dataArray['alternateLink'];
            if (key_exists('iconLink', $dataArray))
                $this->icon = $dataArray['iconLink'];
            if (key_exists('thumbnailLink', $dataArray))
                $this->thumbnail = $dataArray['thumbnailLink'];
            if (key_exists('createdDate',$dataArray))
                $this->createdTime = date('m.d.Y H:i:s', strtotime($dataArray['createdDate']));
            if (key_exists('modifiedDate',$dataArray))
                $this->updatedTime = date('m.d.Y H:i:s', strtotime($dataArray['modifiedDate']));
            if (key_exists('etag', $dataArray))
                $this->etag = $dataArray['etag'];
            if (key_exists('exportLinks', $dataArray))
                $this->exportLinks = ExportLinks::initFromArray($dataArray['exportLinks']);
            if (key_exists('trashed', $dataArray['labels']))
                $this->trashed = $dataArray['labels']['trashed'];
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function isTrashed() {
        return $this->trashed;
    }
    
    public function isViewed() {
        return $this->viewed;
    }
    
    public function setViewed($viewed) {
        $this->viewed = $viewed;
    }
    
    public function isRestricted() {
        return $this->restricted;
    }
    
    public function setRestricted($restricted) {
        $this->restricted = $restricted;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function getCreatedTime() {
        return $this->createdTime;
    }
    
    public function setCreatedTime($createdTime) {
        $this->createdTime = $createdTime;
    }
    
    public function getUpdatedTime() {
        return $this->updatedTime;
    }
    
    public function setUpdatedTime($time) {
        $this->updatedTime = date('m.d.Y H:i:s', strtotime($time));
    }
    
    public function getShareLink() {
        return $this->shareLink;
    }
    
    public function setShareLink($shareLink) {
        $this->shareLink = $shareLink;
    }
    
    public function getEmbedLink() {
        return $this->embedLink;
    }
    
    public function getEditLink() {
        return $this->editLink;
    }
    
    public function setEditLink($editLink) {
        $this->editLink = $editLink;
    }
    
    public function getDeleteLink() {
        return $this->deleteLink;
    }
    
    public function setDeleteLink($deleteLink) {
        $this->deleteLink = $deleteLink;
    }
    
    public function getDownloadLink() {
        return $this->downloadLink;
    }
    
    public function setDownloadLink($downloadLink) {
        $this->downloadLink = $downloadLink;
    }
    
    public function getEtag() {
        return $this->etag;
    }
    
    public function setEtag($etag) {
        $this->etag = $etag;
    }
    
    public function isEditable() {
        return $this->editable;
    }
    
    public function setIsEditable($editable) {
        $this->editable = $editable;
    }
    
    public function getIcon() {
        return $this->icon;
    }
    
    public function getThumbnail() {
        return $this->thumbnail;
    }
}