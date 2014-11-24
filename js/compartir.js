// JavaScript Document

function compartirFacebook(url){
	window.open("http://www.facebook.com/sharer.php?u="+encodeURIComponent(url),"Facebook","toolbar=0, status=0, width=650, height=450");
}

function compartirTwitter(url){
	window.open("http://www.twitter.com/share?url="+encodeURIComponent(url),"Twitter","toolbar=0, status=0, width=650, height=450");
}