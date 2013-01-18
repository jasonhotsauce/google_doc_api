<div class="wrap">
            <?php if(!$hasToken){?>
            <button id="access_button" type="button" data-destination="<?php echo site_url('home/gainToken');?>">Allow us access your Google Drive contents</button>
            <?php }?>
            <table>
                <tr>
                    <th> &nbsp;</th>
                    <th width="48%">Title</th>
                    <th width="30%">Last Modifed</th>
                    <th width="5%">&nbsp;</th>
                    <th width="5%">&nbsp;</th>
                    <th width="5%">&nbsp;</th>
                    <th width="5%">&nbsp;</th>
                </tr>
           <?php if(isset($documents) && !empty($documents)){ 
                    foreach($documents as $document){
                        if(!$document->isTrashed()){
                        ?>
                    
                <tr id="<?php echo $document->getId();?>">
                    <td><img src="<?php echo $document->getIcon();?>" style="vertical-align:middle;"/></td>
                    <td <?php //if($document->getType() == DOC_TYPE_FOLDER;?> <?php if($document->isViewed()){?>style="font-weight:bold;"<?php }?>><?php echo $document->getTitle();?></td>
                    <td style="text-align:center"><?php echo $document->getUpdatedTime();?></td>
                    <td><a class="modify_share" href="<?php echo site_url('home/share');?>" data-destination="<?php echo rawurlencode($document->getShareLink());?>" style="text-decoration:none"><span class="ui-icon ui-icon-person" title="Share"></span></a></td>
                    <td><span class="ui-icon ui-icon-comment edit_doc" data-destination="<?php echo $document->getEditLink();?>" title="Edit"></span></td>
                    <td><span class="ui-icon ui-icon-disk download_doc" data-destination="<?php echo $document->getDownloadLink();?>" title="Download"></span></td>
                    <td><span class="ui-icon ui-icon-trash delete_doc" data-url="<?php echo site_url('home/trashDoc');?>" data-etag="<?php echo urlencode($document->getEtag());?>" data-id="<?php echo urlencode($document->getId());?>" title="Delete"></span></td>
                </tr>
            <?php }}}?>
            </table>
        </div>