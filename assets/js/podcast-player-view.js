import { PodCastPlayer } from "./podcast-player";

const players = document.getElementsByClassName('wp-block-podcasting-podcast');

for( let i=0; i<players.length; i++ ){
	let caption = null;
	const figcaption = players[i].getElementsByTagName('figcaption');

	if ( figcaption && figcaption.length>0 ) {
		caption = figcaption[0].innerHTML;
	}
	
	ReactDOM.render(<PodCastPlayer post_id={window.simple_podcasting_data.post_id} caption={caption}/>, players[i] )
}