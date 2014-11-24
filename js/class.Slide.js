var ASlide = new Class({
	options: {
		showControls: false,
		showDuration:2500,
		showTOC: false,
		tocWidth: 20,
		tocClass: 'toc',
		tocActiveClass: 'toc-active'
	},
	Implements: [Options,Events],
	initialize: function(container,elements,options) {
		//settings
		this.container = $(container);
		this.elements = $$(elements);
		this.currentIndex = 0;
		this.interval = '';
		if(this.options.showTOC) this.toc = [];
		
		/** solo para dulcesorpresa**/
		
		var capa= new Element('div',{
			id:'div__linksACarrusel',
			styles:{
				//backgroundImage:"url(images/pestana_1.png)",
				width:105 * this.elements.length,
				height:27,
				top:264,
				//left:360,
				position:'relative',
				float : 'right',
				zIndex:500
			},
			align:'right'
		});
		
		/*****/
		var y=0;
		//assign
		this.elements.each(function(el,i){
			y++;
			capa.inject(this.container);
			if(this.options.showTOC) {
				//para dulce
				/***/
				var izq=0;
				switch(i){
					case "0":
						izq=30;
					break;
				
					case 1:
						izq=15;
					break;
				
					case 2:
						izq=22;
					break;
				
				
				}	
				/****/
				
								this.toc.push(new Element('a',{
									html: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+(i+1),
									href: '#',
									rel:'nofollow',
									'class': this.options.tocClass + '' + (i == 0 ? ' ' + this.options.tocActiveClass : ''), //modificado para dulce
									//'class': 'toc'+(i+1)+' ' + (i == 0 ? ' ' + 'toc'+(i+1)+'-active' : ''),
									events: {
										click: function(e) {
											if(e) e.stop();
											this.stop();
											this.show(i);
										}.bind(this)
									},
									styles: {
										left: 0+ ((i + 1) * (this.options.tocWidth + izq)) //mpodificado parsa dulce  < ((i + 1) * (this.options.tocWidth + 10)) >
									}
								//}).inject(this.container));
								}).inject(capa));	
							}
			
			if(i > 0) el.set('opacity',0);
			
			
		},this);
		
		if(y==1){capa.hide();};
		
		
		//next,previous links
		if(this.options.showControls) {
			this.createControls();
			
		}
		//events
		this.container.addEvents({
			mouseenter: function() { this.stop(); }.bind(this),
			mouseleave: function() { this.start(); }.bind(this)
		});

	},
	show: function(to) {
		//alert(this.currentIndex);
		/*para dulce*/
		
		/**/
		
		
		this.elements[this.currentIndex].set("tween",{duration:800});
		
		//this.elements[this.currentIndex].fade('out');
		this.elements[this.currentIndex].tween('opacity',0);
		
		if(this.options.showTOC) this.toc[this.currentIndex].removeClass(this.options.tocActiveClass);
		this.elements[this.currentIndex = ($defined(to) ? to : (this.currentIndex < this.elements.length - 1 ? this.currentIndex+1 : 0))].tween("opacity",1);//.fade('in');
		if(this.options.showTOC) this.toc[this.currentIndex].addClass(this.options.tocActiveClass);
	},
	start: function() {
		this.interval = this.show.bind(this).periodical(this.options.showDuration);
	},
	stop: function() {
		$clear(this.interval);
	},
	//"private"
	createControls: function() {
		var next = new Element('a',{
			href: '#',
			id: 'next',
			text: '>>',
			events: {
				click: function(e) {
					if(e) e.stop();
					this.stop(); 
					this.show();
				}.bind(this)
			}
		}).inject(this.container); 
		var previous = new Element('a',{
			href: '#',
			id: 'previous',
			text: '<<',
			events: {
				click: function(e) {
					if(e) e.stop();
					this.stop(); 
					this.show(this.currentIndex != 0 ? this.currentIndex -1 : this.elements.length-1);
				}.bind(this)
			}
		}).inject(this.container); 
	}
});