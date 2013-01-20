<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class is an object of google drive file.
 * @author Wenbin Zhang
 *
 */
class Document extends DataModel {
    const DOC_TYPE_FOLDER = "application/vnd.google-apps.folder";
    public $mimeType;
    public $thumbnailLink;
    protected $__labelsType = 'Label';
    public $labels;
    protected $__indexableTextType = 'IndexableText';
    public $indexableText;
    public $explicitlyTrashed;
    public $etag;
    public $lastModifyingUserName;
    public $writersCanShare;
    public $id;
    public $title;
    public $ownerNames;
    public $sharedWithMeDate;
    public $lastViewedByMeDate;
    protected $__parentsType = 'ParentReference';
    public $parents;
    public $exportLinks;
    public $originalFilename;
    public $description;
    public $webContentLink;
    public $editable;
    public $kind;
    public $quotaBytesUsed;
    public $fileSize;
    public $createdDate;
    public $md5Checksum;
//     protected $__imageMediaMetadataType = 'ImageMetaDataType';
    public $imageMediaMetadata;
    public $embedLink;
    public $alternateLink;
    public $iconLink;
    public $modifiedByMeDate;
    public $downloadUrl;
    public $__userPermissionType = 'Permission';
    public $userPermission;
    public $fileExtension;
    public $selfLink;
    public $modifiedDate;
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
    }
    public function getMimeType() {
        return $this->mimeType;
    }
    public function setThumbnailLink($thumbnailLink) {
        $this->thumbnailLink = $thumbnailLink;
    }
    public function getThumbnailLink() {
        return $this->thumbnailLink;
    }
    public function setLabels(Label $labels) {
        $this->labels = $labels;
    }
    public function getLabels() {
        return $this->labels;
    }
    public function setIndexableText(IndexableType $indexableText) {
        $this->indexableText = $indexableText;
    }
    public function getIndexableText() {
        return $this->indexableText;
    }
    public function setExplicitlyTrashed($explicitlyTrashed) {
        $this->explicitlyTrashed = $explicitlyTrashed;
    }
    public function getExplicitlyTrashed() {
        return $this->explicitlyTrashed;
    }
    public function setEtag($etag) {
        $this->etag = $etag;
    }
    public function getEtag() {
        return $this->etag;
    }
    public function setLastModifyingUserName($lastModifyingUserName) {
        $this->lastModifyingUserName = $lastModifyingUserName;
    }
    public function getLastModifyingUserName() {
        return $this->lastModifyingUserName;
    }
    public function setWritersCanShare($writersCanShare) {
        $this->writersCanShare = $writersCanShare;
    }
    public function getWritersCanShare() {
        return $this->writersCanShare;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }
    public function setTitle($title) {
        $this->title = $title;
    }
    public function getTitle() {
        return $this->title;
    }
    public function setOwnerNames(/* array(string) */ $ownerNames) {
        $this->assertIsArray($ownerNames, 'string', __METHOD__);
        $this->ownerNames = $ownerNames;
    }
    public function getOwnerNames() {
        return $this->ownerNames;
    }
    public function setSharedWithMeDate($sharedWithMeDate) {
        $this->sharedWithMeDate = $sharedWithMeDate;
    }
    public function getSharedWithMeDate() {
        return $this->sharedWithMeDate;
    }
    public function setLastViewedByMeDate($lastViewedByMeDate) {
        $this->lastViewedByMeDate = $lastViewedByMeDate;
    }
    public function getLastViewedByMeDate() {
        return $this->lastViewedByMeDate;
    }
    public function setParents(/* array(ParaentReference) */ $parents) {
        $this->assertIsArray($parents, 'ParentReference', __METHOD__);
        $this->parents = $parents;
    }
    public function getParents() {
        return $this->parents;
    }
    public function setExportLinks($exportLinks) {
        $this->exportLinks = $exportLinks;
    }
    public function getExportLinks() {
        return $this->exportLinks;
    }
    public function setOriginalFilename($originalFilename) {
        $this->originalFilename = $originalFilename;
    }
    public function getOriginalFilename() {
        return $this->originalFilename;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function getDescription() {
        return $this->description;
    }
    public function setWebContentLink($webContentLink) {
        $this->webContentLink = $webContentLink;
    }
    public function getWebContentLink() {
        return $this->webContentLink;
    }
    public function setEditable($editable) {
        $this->editable = $editable;
    }
    public function getEditable() {
        return $this->editable;
    }
    public function setKind($kind) {
        $this->kind = $kind;
    }
    public function getKind() {
        return $this->kind;
    }
    public function setQuotaBytesUsed($quotaBytesUsed) {
        $this->quotaBytesUsed = $quotaBytesUsed;
    }
    public function getQuotaBytesUsed() {
        return $this->quotaBytesUsed;
    }
    public function setFileSize($fileSize) {
        $this->fileSize = $fileSize;
    }
    public function getFileSize() {
        return $this->fileSize;
    }
    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;
    }
    public function getCreatedDate() {
        return $this->createdDate;
    }
    public function setMd5Checksum($md5Checksum) {
        $this->md5Checksum = $md5Checksum;
    }
    public function getMd5Checksum() {
        return $this->md5Checksum;
    }
    public function setImageMediaMetadata(ImageMetaData $imageMediaMetadata) {
        $this->imageMediaMetadata = $imageMediaMetadata;
    }
    public function getImageMediaMetadata() {
        return $this->imageMediaMetadata;
    }
    public function setEmbedLink($embedLink) {
        $this->embedLink = $embedLink;
    }
    public function getEmbedLink() {
        return $this->embedLink;
    }
    public function setAlternateLink($alternateLink) {
        $this->alternateLink = $alternateLink;
    }
    public function getAlternateLink() {
        return $this->alternateLink;
    }
    public function getIconLink() {
        return $this->iconLink;
    }
    public function setIconLink($iconLink) {
        $this->iconLink = $iconLink;
    }
    public function setModifiedByMeDate($modifiedByMeDate) {
        $this->modifiedByMeDate = $modifiedByMeDate;
    }
    public function getModifiedByMeDate() {
        return $this->modifiedByMeDate;
    }
    public function setDownloadUrl($downloadUrl) {
        $this->downloadUrl = $downloadUrl;
    }
    public function getDownloadUrl() {
        return $this->downloadUrl;
    }
    public function setUserPermission(Permission $userPermission) {
        $this->userPermission = $userPermission;
    }
    public function getUserPermission() {
        return $this->userPermission;
    }
    public function setFileExtension($fileExtension) {
        $this->fileExtension = $fileExtension;
    }
    public function getFileExtension() {
        return $this->fileExtension;
    }
    public function setSelfLink($selfLink) {
        $this->selfLink = $selfLink;
    }
    public function getSelfLink() {
        return $this->selfLink;
    }
    public function setModifiedDate($modifiedDate) {
        $this->modifiedDate = $modifiedDate;
    }
    public function getModifiedDate() {
        return $this->modifiedDate;
    }
}

class ParentReference extends DataModel {
    public $id;
    public $kind;
    public $selfLink;
    public $parentLink;
    public $isRoot;
    
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getKind() {
        return $this->kind;
    }
    public function setKind($kind) {
        $this->kind = $kind;
    } 
    public function getSelfLink() {
        return $this->selfLink;
    }
    public function setSelfLink($selfLink) {
        $this->selfLink = $selfLink;
    }
    public function getParentLink() {
        return $this->parentLink;
    }
    public function setParentLink($parentLink) {
        $this->parentLink = $parentLink;
    }
    public function isRoot() {
        return $this->isRoot;
    }
    public function setIsRoot($isRoot) {
        $this->isRoot = $isRoot;
    }
}

class ChildReference extends DataModel {
    public $id;
    public $kind;
    public $selfLink;
    public $childLink;
    
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getKind() {
        return $this->kind;
    }
    public function setKind($kind) {
        $this->kind = $kind;
    }
    public function getSelfLink() {
        return $this->selfLink;
    }
    public function setSelfLink($selfLink) {
        $this->selfLink = $selfLink;
    }
    public function getchildLink() {
        return $this->childLink;
    }
    public function setChildLink($childLink) {
        $this->childLink = $childLink;
    }
}

class Label extends DataModel {
    public $restricted;
    public $viewed;
    public $starred;
    public $hidden;
    public $trashed;
    
    public function getRestricted() {
        return $this->restricted;
    }
    public function setRestricted($restricted) {
        $this->restricted = $restricted;
    }
    public function getViewed() {
        return $this->viewed;
    }
    public function setViewed($viewed) {
        $this->viewed = $viewed;
    }
    public function getStarred() {
        return $this->starred;
    }
    public function setStarred($starred) {
        $this->starred = $starred;
    }
    public function getHidden() {
        return $this->hidden;
    }
    public function setHidden($hidden) {
        $this->hidden = $hidden;
    }
    public function getTrashed() {
        return $this->trashed;
    }
    public function setTrashed($trashed) {
        $this->trashed = $trashed;
    }
}

class Permission extends DataModel {
    public $kind;
    public $etag;
    public $id;
    public $selfLink;
    public $name;
    public $role;
    public $additionalRoles;
    public $type;
    public $value;
    public $authKey;
    public $withLink;
    public $photoLink;
    public function getKind() {
        return $this->kind;
    }
    public function setKind($kind) {
        $this->kind = $kind;
    }
    public function getEtag() {
        return $this->etag;
    }
    public function setEtag($etag) {
        $this->etag = $etag;
    }
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getSelfLink() {
        return $this->selfLink;
    }
    public function setSelfLink($selfLink) {
        $this->selfLink = $selfLink;
    }
    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function getRole() {
        return $this->role;
    }
    public function setRole($role) {
        $this->role = $role;
    }
    public function getAdditionalRoles() {
        return $this->additionalRoles;
    }
    public function setAdditionalRoles(/*array(string)*/$additionalRoles) {
        $this->assertIsArray($additionalRoles, 'String', __METHOD__);
        $this->additionalRoles = $additionalRoles;
    }
    public function getType() {
        return $this->type;
    }
    public function setType($type) {
        $this->type = $type;
    }
    public function getValue() {
        return $this->value;
    }
    public function setValue($value) {
        $this->value = $value;
    }
    public function getAuthKey() {
        return $this->authKey;
    }
    public function setAuthKey($authKey) {
        $this->authKey = $authKey;
    }
    public function getWithLink() {
        return $this->withLink;
    }
    public function setWithLink($withLink) {
        $this->withLink = $withLink;
    }
    public function getPhotoLink() {
        return $this->photoLink;
    }
    public function setPhotoLink($photoLink) {
        $this->photoLink = $photoLink;
    }
}

class IndexableText extends DataModel {
  public $text;
  public function setText($text) {
    $this->text = $text;
  }
  public function getText() {
    return $this->text;
  }
}

class FileList extends DataModel {
    public $nextPageToken;
    public $kind;
    protected $__itemsType = 'Document';
    protected $__itemsDataType = 'array';
    public $items;
    public $nextLink;
    public $etag;
    public $selfLink;
    public function setNextPageToken($nextPageToken) {
        $this->nextPageToken = $nextPageToken;
    }
    public function getNextPageToken() {
        return $this->nextPageToken;
    }
    public function setKind($kind) {
        $this->kind = $kind;
    }
    public function getKind() {
        return $this->kind;
    }
    public function setItems(/* array(Google_DriveFile) */ $items) {
        $this->assertIsArray($items, 'Document', __METHOD__);
        $this->items = $items;
    }
    public function getItems() {
        return $this->items;
    }
    public function setNextLink($nextLink) {
        $this->nextLink = $nextLink;
    }
    public function getNextLink() {
        return $this->nextLink;
    }
    public function setEtag($etag) {
        $this->etag = $etag;
    }
    public function getEtag() {
        return $this->etag;
    }
    public function setSelfLink($selfLink) {
        $this->selfLink = $selfLink;
    }
    public function getSelfLink() {
        return $this->selfLink;
    }
}

class ChildList extends DataModel {
    public $kind;
    public $etag;
    public $selfLink;
    public $nextPageToken;
    public $nextLink;
    protected $__itemsType = 'ChildReference';
    public $items;
    
    public function getKind() {
        return $this->kind;
    }
    public function setKind($kind) {
        $this->kind = $kind;
    }
    public function getEtag() {
        return $this->etag;
    }
    public function setEtag($etag) {
        $this->etag = $etag;
    }
    public function getSelfLink() {
        return $this->selfLink;
    }
    public function setSelfLink($selfLink) {
        $this->selfLink = $selfLink;
    }
    public function getNextPageToken() {
        return $this->nextPageToken;
    }
    public function getNextLink() {
        return $this->nextLink;
    }
    public function setNextLink($nextLink) {
        $this->nextLink = $nextLink;
    }
    public function getItems() {
        return $this->items;
    }
    public function setItems(/*array(ChildReference)*/$items) {
        $this->assertIsArray($items, 'ChildReference', __METHOD__);
        $this->items = $items;
    }
}