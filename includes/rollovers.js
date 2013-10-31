      if (document.images) {

            img1on = new Image();      		// Active Images
            img1on.src = "/images/home_on.gif"; 
            img2on = new Image(); 
            img2on.src = "/images/news_on.gif";  
            img3on = new Image();
            img3on.src = "/images/faq_on.gif";
            img4on = new Image();
            img4on.src = "/images/learn_on.gif";       
            img5on = new Image();
            img5on.src = "/images/projects_on.gif";                
            img6on = new Image();
            img6on.src = "/images/license_on.gif";                
            img7on = new Image();
            img7on.src = "/images/discuss_on.gif";    
			
            img1off = new Image();         	// Inactive Images
            img1off.src = "/images/home_off.gif"; 
            img2off = new Image(); 
            img2off.src = "/images/news_off.gif";  
            img3off = new Image();
            img3off.src = "/images/faq_off.gif";
            img4off = new Image();
            img4off.src = "/images/learn_off.gif";               
            img5off = new Image();
            img5off.src = "/images/projects_off.gif";                
            img6off = new Image();
            img6off.src = "/images/license_off.gif";                
            img7off = new Image();
            img7off.src = "/images/discuss_off.gif";                
	   }

// Function to 'activate' images.

function imgOn(imgId) {
        if (document.images) {
            document[imgId].src = eval(imgId + "on.src");
		
        }
}

// Function to 'deactivate' images.

function imgOff(imgId) {
        if (document.images) {
              document[imgId].src = eval(imgId + "off.src");
				
        }
}

