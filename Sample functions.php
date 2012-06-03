<?php
/*
 these function were written as part of the CakePHP framework
 so some of the semantics used confirm with that used in CakePHP
*/
function categoryAdd($param=null) // function writes new child with attributes in a XML file.
 {
 	   //$this->autoRender = false;
       //  get the category id and the category name
	   $categoryId = ($this->data['add']['category']);

	   $categoryName = $this->Job->categoryName($categoryId);



	   $filename = UPLOAD_DIR.'/files/category.xml';
	   $induName; $induId;
       if (file_exists($filename)) {

            $xml = simplexml_load_file($filename);// loads the contents of the xml file in a xml object
            $xpathExp= '/industry/spotlightindustry[@name="'.$categoryName.'"]'; // searches the xml array using xpath for the category name
            $result = $xml->xpath($xpathExp);

				  if(!empty($result)) // if category exists nothing is written in the file
					{

						$this->flash('Category exists already', '/admin/seo-manager',3);
						exit;
					}

					else // else a child with the name of the category is written in the xml file
					{
					    $spotInd= $xml->addChild('spotlightindustry');
							$spotInd->addAttribute('name', $categoryName); // attribure name and id is added
							$spotInd->addAttribute('id', $categoryId);
							$spotInd->addChild('activate','A');// child Activate is added
							$spotInd->addChild('metatitle',' '); 
							$spotInd->addChild('metadescription',' ');
							$spotInd->addChild('keywords',' ');
							$spotInd->addChild('content',' ');

					    if($xml->asXML($filename)== true)
		  		         {
						 	 $this->flash('The category was succesfully added', '/admin/seo-manager',3);
				             exit;
						 }
						else
						{
							$this->flash('Could not add category. Try again !!', '/admin/seo-manager',3);
				            exit;
						}
					}
		    }
			else {
                   exit('Failed to open file.');
             }

 }
 /*
 This function updates the values of attributes in a XML file.
 */
	function categoryUpdate() 
	{

	      $filename = UPLOAD_DIR.'/files/category.xml';

	       $industryTypeId = $this->data['Page']['category'];// gets the category Id

       	   $deactivate = $this->data['Page']['deactivate'];
       	   $metatitle  = $this->data['Page']['Title'];
       	   $metakeywords= $this->data['Page']['keywords'];
       	   $metadesc = $this->data['Page']['description'];
       	   $metacontent = $this->data['Page']['seocontent']; // gets the page contents


       	    if (file_exists($filename)) {

               $xmlFileData = simplexml_load_file($filename); // gets the file contents 

               $xmlPathQuery= '/industry/spotlightindustry[@id="'.$industryTypeId.'"]'; // search for the specific industry id
               $result = $xmlFileData->xpath($xmlPathQuery); 

              $i = 0;
			  foreach($xmlFileData as $key => $value) // loop thorugh the XML objectarray
              {

               
              	   foreach($value->attributes() as $industryXmlAttr => $industryAttrValue)
					{

					   	if($industryXmlAttr=='id') // if the attribute is id 
					    {
						  	if($industryTypeId == $industryAttrValue ) // if the industryid matches with attribute value
						  	{ // update the values of other child in the node
						  	    $xmlFileData->spotlightindustry[$i]->activate = trim($deactivate);
								$xmlFileData->spotlightindustry[$i]->metatitle = trim($metatitle);
								$xmlFileData->spotlightindustry[$i]->metadescription = trim($metadesc);
								$xmlFileData->spotlightindustry[$i]->keywords =  trim($metakeywords);
								$xmlFileData->spotlightindustry[$i]->content = trim($metacontent);


	  		                    if($xmlFileData->asXML($filename)== true)
	  		                    {
						    	  $this->flash('Changes successfully implemented', '/admin/seo-manager',1);
			                         exit;
								}
								else
								{
								 $this->flash('Changes could not be made. Try again !!', '/admin/seo-manager',1);
			                         exit;
								}
						  	}

						}

				   }

			    $i++;

           }

         }
         else {
               $this->flash('Failed to open file ! Try again!', '/admin/seo-manager',1);
			   exit;
         }

   }

?>