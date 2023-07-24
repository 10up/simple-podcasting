import { PodCastPlayer } from "./podcast-player";

const players = document.getElementsByClassName('wp-block-podcasting-podcast');
console.log(players);
for( let i=0; i<players.length; i++ ){
	ReactDOM.render(<PodCastPlayer/>, players[i] )
}