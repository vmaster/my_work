/**
Class Ventana

require class.Carrusel
**/

var Ventana = new Class({
    Implements: [Options, Events],
    options: {
        modal: false,
        ancho: 350,
        titulo: '',
        alto: 350,
        id: "VentanaMoo",
        fondo: "white",
        fondoModal: "#000",
        opacidad: 0.2,
        html: "",
        url: "",
        arrastra: false,
        separacion: 10,
        borde: 1,
        btnCerrar: "",
        imagenFondo: "",
        colorfondoCabecera: "",
        colorBorde: "",
        textoCerrar: "",
        anchoPrivado: 35,
        htmlCerrar: "",
        estiloCabecera: "",
        onCerrar: $empty(),
        onLoad: $empty(),
	    move:false
    },
    initialize: function(options){
        this.setOptions(options);
        av = window.getWidth() / 2;
        alv = window.getHeight() / 2;
        ad = this.options.ancho / 2;
        ald = this.options.alto / 2;
        ventana = new Element("div", {
            id: this.options.id,
            styles: {
                backgroundColor: this.options.fondo,
                padding: this.options.separacion + 'px',
                height: this.options.alto + 'px',
                width: this.options.ancho + 'px',
                position: 'absolute',
                border: this.options.borde + 'px solid #CCC',
                left: parseInt(av) - parseInt(ad),
                top: parseInt(alv) - parseInt(ald),
                zIndex: 1200,
                display: 'none'
            }
        
        });
        
        
            ventana.set('html', '<div id="header__moo" class="cabecera__moo" style="padding:10px;height:15px" >' +
            '<div style="width:85%;float:left;">' +
            this.options.titulo +
            '</div>' +
            '<div align="right" id="cierra_v_moo" style="float:left;width:15%;cursor:pointer;" title=""></div>' +
            '</div>' +
            '<div style="border-top:0px solid #CCC;padding:35px;height:' +
            parseInt(this.options.alto - 25) +
            'px;overflow:hidden;vertical-align:middle" align="center" id="conte__moo" class="cuerpo__moo">' +
            '</div>');
            
        
        
    },
    render: function(){
        if (!this.options.modal) {
            //$$('select').setStyle("visibility", "hidden");
            $$('body').grab(ventana);
            
            
                $('cierra_v_moo').addEvent('click', this.cerrar.bind(this));
            
            
            if (this.options.url == "") {
                $('conte__moo').set('html', this.options.html);
            }
            else {
                $('conte__moo').set("load", {
                    evalScripts: true,
                    evalResponse: true,
                    onSuccess: function(){
                        this.fireEvent('load');
                    }.bind(this)
                });
                $('conte__moo').load(this.options.url);
            }
            
        }
        else {
            var modal = new Element('div', {
                id: "VentanaMooModal",
                styles: {
                    backgroundColor: this.options.fondoModal,
                    opacity: this.options.opacidad,
                    height: window.getScrollHeight(),
                    width: window.getScrollWidth(),
                    zIndex: 1009,
                    position: 'absolute',
                    top: '0px',
                    left: '0px',
                    visibility: 'hidden'
                }
            });
            //$$('select').setStyle("visibility", "hidden");
            $$('body').grab(modal);
            modal.tween("opacity", [0, this.options.opacidad]);
            //modal.fadeTo(this.options.opacidad);
            $$('body').grab(ventana);
            
            if (this.options.separacion == 0) {
                $('conte__moo').setStyle("padding", "0px");
            }
            
            if (this.options.htmlCerrar != "") {
                $('cierra_v_moo').set("html", this.options.htmlCerrar);
            }
            if (this.options.colorBorde != "") {
                ventana.setStyles({
                    "border-color": this.options.colorBorde
                });
            }
            if (this.options.estiloCabecera != "") {
                $('header__moo').addClass(this.options.estiloCabecera);
            }
            if (this.options.imagenFondo != "") {
                $('conte__moo').setStyle("background-image", "url(" + this.options.imagenFondo + ")");
                $('conte__moo').setStyle("background-repeat", "repeat-x");
            }
            
            this.posicionar();
            if (this.options.url == "") {
                $('conte__moo').set('html', this.options.html);
            }
            else {
               
                    $('conte__moo').set('load', {
                        'evalResponse': true,
                        'onSuccess': function(){
                            if ($(this.options.btnCerrar) && this.options.url) {
                                $(this.options.btnCerrar).addEvent('click', this.cerrar.bind(this)).setStyle("cursor", "pointer").set("title", "");
                            }
                            else {
                                $('cierra_v_moo').addEvent('click', this.cerrar.bind(this));
                            }
                            this.fireEvent('load');
                        }.bind(this)
                    });
                    
              
                
                $('conte__moo').load(this.options.url);
            }
        }
        
        if (this.options.arrastra == "si") {
            if ($('header__moo')) {
                $('header__moo').setStyle("cursor", "move");
                $(ventana).makeDraggable({
                    handle: 'header__moo'/*,container:document.body*/
                });
            }
            
        }
        
        document.addEvents({
            'keypress': function(e){
                if (e.key == 'esc') {
                    if ($('conte__moo')) {
                        this.cerrar();
                    }
                }
            }.bind(this)
        });
		
		if(this.options.move){
		
			window.addEvents({
				'resize': function(){
					if ($('conte__moo')) {
						//	$('VentanaMooModal').morph({"width":window.getScrollWidth(),"height":window.getScrollHeight()});
						this.posicionar();
					}
					
				}.bind(this)            ,
				"scroll": function(){
					if ($('conte__moo')) {
						//$('VentanaMooModal').morph({"width":window.getScrollWidth(),"height":window.getScrollHeight()});
						this.posicionar();
					}
				}.bind(this)
			});
		}
        if ($('VentanaMooModal')) {
            $('VentanaMooModal').addEvent("click", function(){
                if ($('conte__moo')) {
                    this.cerrar();
                }
            }.bind(this));
            
        }
        
        
        
        
        var pos = window.getHeight();
        var posAncho = window.getWidth();
        sizes = window.getSize();
        scrollito = window.getScroll();
        
        
        ventana.setStyles({
            //top:0 + defaz.toInt()  
            'top': (scrollito.y + (sizes.y - this.options.alto) / 2).toInt()
        });
        
        
    },
    cerrar: function(){
        //window.removeEvent('keypress',function(e){});
        //$$('select').setStyle("visibility", "visible");
        $('VentanaMooModal').set("tween", {
            duration: 500,
            onComplete: function(){
                $('VentanaMooModal').destroy();
            }
        });
        if ($(this.options.id)) {
            $(this.options.id).destroy();
        }
        $('VentanaMooModal').tween("opacity", [this.options.opacidad, 0])
        
        //$$('select').setStyle("visibility", "visible");
        this.fireEvent('cerrar');
    },
    load: function(){
    },
    posicionar: function(){
        var scrollventana = new Fx.Scroll(window, {
            wait: false,
            duration: 800,
            transition: Fx.Transitions.Sine.easeInOut
        });
       
            var pos = window.getHeight();
            var posAncho = window.getWidth();
            sizes = window.getSize();
            scrollito = window.getScroll();
            //ventana.setStyles({"top":0,"display":"none"});
            /*
             ventana.setStyles({
             //top:0 + defaz.toInt()
             'top': (scrollito.y + (sizes.y - this.options.alto) / 2).toInt()
             });
             */
            
                //$('VentanaMooModal').morph({"width":window.getScrollWidth(),"height":window.getScrollHeight()});
                //ventana.setStyles({'top': (scrollito.y + (sizes.y - this.options.alto) / 2).toInt()});
                ventana.set('morph', {
                    duration: 500,
                    transition: 'sine:in:out',
                    onComplete: function(){
                        //scrollventana.toElement($('conte__moo'));
                        ventana.setStyles({
                            "display": "block"
                        });
					
                    }
                }).morph({
				'top': (scrollito.y + (sizes.y - this.options.alto) / 2).toInt(),
				"left":posAncho / 2 - (this.options.ancho / 2).toInt()
				
				/*'top': (scrollito.y + (sizes.y - this.options.alto) / 2).toInt(),
				
                     "left":posAncho / 2 - (this.options.ancho / 2).toInt()
                     */
                });
                $('cierra_v_moo').addEvent('click', this.cerrar.bind(this));
           
       
    }
});