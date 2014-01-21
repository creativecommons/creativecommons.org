Overarching Infrastructure
==========================

This section is for describing how the overall infrastructure of
cc.engine and its related components (including license
deployment/management and etc) work together.


Infrastructure annotation
-------------------------

The following is a diagram of how all the cc infrastructure works
together::

    *******************
    * CORE COMPONENTS *
    *******************
    
          .--.
         ( o_o)
         /'---\
         |USER| --.
         '----'   |
                  |
                  V
             ___   .---.
           .'   ','     '.
         -'               '.
        (     INTARWEBS     ) 
         '_.     ____    ._'
            '-_-'    '--'
                  |
                  |
                  V
          +---------------+  Web interface user
          |   cc.engine   |  interacts with
          +---------------+  
                  |
                  |
                  V
          +---------------+  Abstraction layer for
          |  cc.license   |  license querying and
          +---------------+  pythonic license API
                  |
                  |
                  V
          +---------------+  Actual rdf datastore and
          |  license.rdf  |  license RDF operation tools
          +---------------+  
    
    
    ****************
    * OTHER PIECES *
    ****************
    
      +--------------+
      |  cc.i18npkg  |
      | .----------. |  
      | | i18n.git | |
      +--------------+
    
    
    ********************************************
    * COMPONENTS DEPRECATED BY SANITY OVERHAUL *
    ********************************************
    
      +------------+  +-----------+  +---------+  +-------------+
      |    old     |  | old zope  |  | licenze |  | license_xsl |
      | cc.license |  | cc.engine |  +---------+  +-------------+
      +------------+  +-----------+  

