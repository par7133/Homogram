# Homogram     
  
Hello and welcome to Homogram!   
	   
Homogram is a light and simple software on premise to share your images. And it can be used as a private repository (screenshots, private shots, etc).   
	   
Homogram is released under GPLv3 license, it is supplied AS-IS and we do not take any responsibility for its misusage.   
	   
The name *Homogram* comes from a prank on the name 'instagram', in fact differently from the latter Homogram gives priorities to homines. However Homogram doesn't birth as its replacement but just like its alter ego.     
     
First step, use the left side panel password and salt fields to create the hash to insert in the config file. Remember to manually set there also the salt value.   
	   
As you are going to run Homogram in the PHP process context, using a limited web server or phpfpm user, you must follow some simple directives for an optimal first setup:   
 
- Check the write permissions of your "HGRepo" folder in your web app public path; and set its path in the config file.   
- Check the write permissions of your "data" folder in your web app private path; and set its path in the config file.
- Set the default Locale.   
- Set the default Context to PUBLIC or PRIVATE (for a private repository).  
- Every picture folder may contain a "thumbs" subfolder - that you should create - to not lack in performance: file names in thumbs must be same of file names in parent folders.   

Login with the password for the admin view.

Optionally you can request Voyeur to host Homogram for you, for 5 bucks a month each 2GB of space.  

For any need of software additions, plugins and improvements please write to <a href="mailto:info@5mode.com">info@5mode.com</a>  

To help please donate by clicking <a href="https://gaox.io/l/dona1">https://gaox.io/l/dona1</a> and filling the form.  
     
### Screenshot:

 ![Homogram in action](/Public/static/res/screenshot1.jpg)

Feedback: <a href="mailto:code@gaox.io">code@gaox.io</a>   
