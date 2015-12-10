<?php
class CatalogueControl
{
    public $Catalogues = array();
    public $ContentFolderId = null;
    public $Strict = true;
    public $BestQuality = false;	// This option would effect on the speed depending on the size of the image.

    public static $MainImageHeight = 600;
    public static $MainImageWidth = 800;

    public function RenderCatalogues()
    {
        echo '<div class="middel_bg">';

        echo '<div id="top"></div>';
        $this->RenderMainImages();

        echo '</div>';
    }

    public function RenderMainImages()
    {
        echo '<div class="middelPart">';
        if (count($this->Catalogues) > 0)
        {
            ?>

            <div id="navigationControl"></div>
            <div id="container">
                <div id="slider">
                    <ul>
                        <?php

                        foreach ($this->Catalogues as $i => $image)
                        {
                            $imageURL = null;
                            $image = $image->CalculateDimensions($image);

                            if($image->Filename != ''){
                                //dimension of the thumbnails: 800*proportional height
                                $originalURL = ImageUtilities::getcloudfrontUrl('content/image/', $image->Filename);
                                $thumbnailURL = ImageUtilities::getcloudfrontUrl('content/image/', $image->Filename ,Yii::app()->params->image_attributes['catalogues']['large']);

                                //use thumbnail if found
                                if(ImageUtilities::checkRemoteImage($thumbnailURL))
                                {
                                    $imageURL = $thumbnailURL;
                                }
                                //else use original image (if found)
                                elseif(ImageUtilities::checkRemoteImage($originalURL))
                                {
                                    $imageURL = $originalURL;
                                }
                            }

                            if ($imageURL)
                            {
                                list($width, $height) = $image->CalculateDimensionsFromUrl($imageURL);
                                $imageTags = $image->Tags;

                                echo '<li>';

                                if(count($imageTags) > 0)
                                {
                                    $scaleFactor = 1;

                                    // Scalling image tags
                                    $scaleFactor = $image->Width / self::$MainImageWidth;

                                    if($scaleFactor<=0)
                                        $scaleFactor = 1;


                                    echo "<div class=\"bannerlink img" . self::$MainImageWidth . "\">";
                                    echo "<dl style=\"background: url(" . Html::Url($imageURL) . ") no-repeat scroll center top; height:". $height ."px; position:relative; margin-left: auto; margin-right: auto;\">";
                                    echo "<div class=\"hiddenCatalogueImage\"><img src=\"" . Html::Url($imageURL) . "\" /></div> \n";

                                    foreach($imageTags as $imageTag)
                                    {
                                        $banner_tag_x = $imageTag->X / $scaleFactor;
                                        $banner_tag_y = $imageTag->Y / $scaleFactor;
                                        $banner_tag_width = $imageTag->Width / $scaleFactor;
                                        $banner_tag_height = $imageTag->Height / $scaleFactor;

                                        //$extraParams = array('style'=>'width:' . $banner_tag_width . 'px;height:' . $banner_tag_height.'px');

                                        $storeWebsiteAdapter = new StoreWebsiteAdapter();
                                        $storeWebsite = $storeWebsiteAdapter->GetPrimaryByStore(UserSessionManager::GetStoreId());

                                        //fix: if there is % in the url, encode that
                                        if(strpos($imageTag->Url, '%'))
                                        {
                                            $exploded = explode('/', $imageTag->Url);
                                            $searchString = $exploded[2];
                                            $imageTag->Url = "/".$exploded[1]."/".urlencode($exploded[2]);
                                        }

                                        // Strip out default website URL
                                        $imageTagURL = $storeWebsite instanceof StoreWebsite && StringUtilities::StartsWith($imageTag->Url, 'http://' . $storeWebsite->Website) ?
                                            substr($imageTag->Url, strlen('http://' . $storeWebsite->Website)) :
                                            $imageTag->Url;


                                        $productId = explode('/', $imageTagURL);

                                        $extraParams = array('style'=>'width:' . $banner_tag_width . 'px;height:' . $banner_tag_height.'px'.'" id="'.$productId[count($productId)-1].'"');


                                        if(StringUtilities::StartsWithHTTP($imageTagURL))
                                        {
                                            $extraParams['target'] = '_blank';
                                        }

                                        echo "<dd style=\"top:" . $banner_tag_y . "px;left:" . $banner_tag_x . "px\" >";
                                        echo Html::Link('<span>' . $imageTag->Name . '</span>', $imageTagURL, $extraParams);
                                        echo "</dd>";
                                    }
                                    echo "</dl>";
                                    echo "</div>";
                                }
                                else
                                {
                                    echo HTML::Image($imageURL);
                                }
                                echo '</li>';
                            } else {
                                unset ($this->Catalogues[$i]);
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div id="thumbImageBox">
                <ul id="controls">
                    <?php
                    $i = 0;
                    foreach ($this->Catalogues as $image)
                    {
                        $imageURL = null;
                        $thumb = false;
                        $orig = false;
                        $image = $image->CalculateDimensions($image);
                        if($image->Filename != ''){
                            //dimension of the thumbnails: 800*proportional height
                            $originalURL = ImageUtilities::getcloudfrontUrl('content/image/', $image->Filename);
                            $thumbnailURL = ImageUtilities::getcloudfrontUrl('content/image/', $image->Filename ,Yii::app()->params->image_attributes['catalogues']['small']);

                            //use thumbnail if found
                            if(ImageUtilities::checkRemoteImage($thumbnailURL))
                            {
                                $imageURL = $thumbnailURL;
                                $thumb = true;
                            }
                            //else use original image (if found)
                            elseif(ImageUtilities::checkRemoteImage($originalURL))
                            {
                                $imageURL = $originalURL;
                                $orig = true;
                            }
                        }

                        if ($imageURL)
                        {
                            ?>
                            <li id="controls<?php echo $i+1; ?>" class="thumbImage">
                                <a href="javascript:void(0);" rel="<?php echo $i; ?>">
                                    <?php
                                    /*
                                    $base_url = THUMB . '/content/image/' . $image->Filename;
                                    $options = null;
                                    $options .= "&amp;w=147&amp;h=82&amp;zc=1";
                                    $imageURL = $base_url . $options;


                                    echo HTML::Image($imageURL, null, array('class' => 'imgBorder'));
                                    */
                                    if($thumb == true){
                                        echo ImageUtilities::showThumb("content/image/".$image->Filename, '', Yii::app()->params->image_attributes['catalogues']['small'], array('class' => 'imgBorder'));
                                    } elseif($orig == true) {
                                        echo ImageUtilities::showThumb("content/image/".$image->Filename, '', '', array('class' => 'imgBorder'));
                                    }
                                    ?>
                                </a>
                            </li>
                            <?php
                        }
                        $i++;
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        else
        {
            echo '<div id="errorMsg">';
            echo 'No catalogues in this store.';
            echo '</div>';
        }
        echo '</div>';
    }
}
?>
