      if (document.images) {

            img1on = new Image();      		// Active Images
            img1on.src = "/images/donate/commons1.gif"; 
            img2on = new Image(); 
            img2on.src = "/images/donate/remixer1.gif";  
            img3on = new Image();
            img3on.src = "/images/donate/innovator1.gif";
            img4on = new Image();
            img4on.src = "/images/donate/donate1.gif";       

            img1off = new Image();         	// Inactive Images
            img1off.src = "/images/donate/commons2.gif"; 
            img2off = new Image(); 
            img2off.src = "/images/donate/remixer2.gif";  
            img3off = new Image();
            img3off.src = "/images/donate/innovator2.gif";
            img4off = new Image();
            img4off.src = "/images/donate/donate2.gif";               
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
