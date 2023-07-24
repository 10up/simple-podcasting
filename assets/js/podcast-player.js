import { useEffect, useState } from "@wordpress/element";

import '../css/podcast-player.scss';

const playlist = [
	{
		artist: 'Johann Pachelbel',
		label: 'Canon in D',
		url: 'http://localhost:10004/wp-content/uploads/2023/06/Happy-birthday-piano-instrumental.mp3'
	},
	{
		artist: 'Johann Pachelbel 2',
		label: 'Canon in D 2',
		url: 'http://localhost:10004/wp-content/uploads/2022/12/file_example_MP3_5MG.mp3'
	},
	{
		artist: 'Johann Pachelbel 3',
		label: 'Canon in D 3',
		url: 'http://localhost:10004/wp-content/uploads/2023/07/2020.01.02.mp3'
	}
]

function formatTime(val) {
  var h = 0, m = 0, s;
  val = parseInt(val, 10);
  if (val > 60 * 60) {
   h = parseInt(val / (60 * 60), 10);
   val -= h * 60 * 60;
  }
  if (val > 60) {
   m = parseInt(val / 60, 10);
   val -= m * 60;
  }
  s = val;
  val = (h > 0)? h + ':' : '';
  val += (m > 0)? ((m < 10 && h > 0)? '0' : '') + m + ':' : '0:';
  val += ((s < 10)? '0' : '') + s;
  return val;
}

export function PodCastPlayer(props) {

	const [state, setState] = useState({
		audio: new Audio(),
		autoplay: false,
		show_playlist: false,
		is_playing: false,
		active_index: props.active_index || 0,
		progress: 0,
		start_time: '00:00',
		end_time: '00:00',
		volume: 100,
		previous_volume: 100,
	});

	state.audio.onplay =()=>{
		console.log(state);
		setState({...state, is_playing: true, autoplay: true});
	}

	state.audio.onpause = ()=>{
		setState({...state, is_playing: false});
	}

	state.audio.ontimeupdate= () =>{
		let {currentTime, duration} = state.audio;

		setState({
			...state,
			start_time: formatTime(currentTime),
			end_time: formatTime(duration),
			progress: currentTime / duration * 100
		});
	}

	state.audio.onended=()=>{
		state.audio.pause();
		state.audio.currentTime = 0;
	}
	
	const changeTrack=(index, call_play=true)=>{
		// state.audio.pause();
		state.audio.currentTime = 0;
		state.audio.src = playlist[index].url;

		if(call_play) {
			state.audio.play();
		}
		
		setState({
			...state, 
			active_index: index
		});
	}

	const seek=(e)=>{
		if (state.audio.readyState == 4) {
			state.audio.currentTime = e.currentTarget.value * state.audio.duration / 100;
		}
	}

	const volumeSet=(e, previous_volume)=>{
		let volume = typeof e == 'object' ? e.currentTarget.value : e;
		state.audio.volume = volume/100;

		setState({
			...state,
			volume,
			previous_volume: previous_volume || volume
		});
	}

	useEffect(()=>{
		changeTrack(0, false);
	}, []);	

	let has_play_list = playlist.length > 1;

	return <div className="simple-audio-player" id="simp">
		<div className="simp-player">
			<div className="simp-display">
				<div className="simp-album w-full flex-wrap">
					<div className="simp-cover">
						<i className="fa fa-music fa-5x"></i>
					</div>
					<div className="simp-info">
						<div className="simp-title">Title</div>
						<div className="simp-artist">Artist</div>
					</div>
				</div>
			</div>
			<div className="simp-controls flex-wrap flex-align">
				<div className="simp-plauseward flex flex-align">
					{has_play_list && <button type="button" className="simp-prev fa fa-backward" disabled={state.active_index==0} onClick={()=>changeTrack(state.active_index-1)}></button> || null}
					<button type="button" className={"simp-plause fa fa-"+(state.is_playing ? 'pause' : 'play')} onClick={()=>state.audio[state.is_playing ? 'pause' : 'play']()}></button>
					{has_play_list && <button type="button" className="simp-next fa fa-forward" disabled={state.active_index >= playlist.length - 1} onClick={()=>changeTrack(state.active_index+1)}></button> || null}
				</div>
				<div className="simp-tracker simp-load">
					<input className="simp-progress" type="range" min="0" max="100" step={.1} value={state.progress} onChange={seek}/>
					<div className="simp-buffer"></div>
				</div>
				<div className="simp-time flex flex-align">
					<span className="start-time">{state.start_time}</span>
					<span className="simp-slash">&#160;/&#160;</span>
					<span className="end-time">{state.end_time}</span>
				</div>
				<div className="simp-volume flex flex-align">
					<button type="button" className={"simp-mute fa fa-volume-"+(state.volume==0 ? 'off' : 'up')} onClick={e=>volumeSet(state.volume==0 ? state.previous_volume : 0, state.previous_volume)}></button>
					<input className="simp-v-slider" type="range" min="0" max="100" step={1} value={state.volume} onChange={volumeSet}/>
				</div>
				<div className="simp-others flex flex-align">
					{has_play_list && <div className="simp-shide">
						<button type="button" className={"simp-shide-bottom fa fa-caret-"+(state.show_playlist ? 'up' : 'down')} title="Show/Hide Playlist" onClick={()=>setState({...state, show_playlist: !state.show_playlist})}></button>
					</div> || null}
				</div>
			</div>
		</div>
		{
			(state.show_playlist && has_play_list) && <div className="simp-playlist">
				<ul>
					{playlist.map((media, index)=>{
						let {url, label, artist} = media;
						return <li key={index} className={state.active_index==index ? "simp-active" : ''} onClick={()=>changeTrack(index)}>
							<span className="simp-source" data-src={url}>{label}</span>
							<span className="simp-desc">{artist}</span>
						</li>
					})}
				</ul>
			</div> || null
		}
		
	</div>
}

/* 
This is block player
<figure key="audio" className={ className }>
	<audio controls="controls" src={ src } />
	{ ( ( caption && caption.length ) || !! isSelected ) && (
		<RichText
			tagName="figcaption"
			placeholder={ __( 'Write caption…', 'simple-podcasting' ) }
			value={ caption }
			onChange={ ( value ) => setAttributes( { caption: value } ) }
			isSelected={ isSelected }
		/>
	) }
</figure>
*/