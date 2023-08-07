import { useEffect, useState } from "@wordpress/element";

const { __ } = wp.i18n;
const { RichText } = wp.blockEditor || {};

import '../css/podcast-player.scss';

export function PodCastPlayer(props) {
	const {post_id, caption, isSelected, setAttributes} = props;

	const [state, setState] = useState({
		active_index    : 0,
		audio           : new Audio(),
		show_playlist   : false,
		is_playing      : false,
		progress        : 0,
		start_time      : '00:00',
		end_time        : '00:00',
		volume          : 100,
		previous_volume : 100,
		fetching        : false,
		playlist        : []
	});

	state.audio.onplay =()=>{
		setState({...state, is_playing: true});
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
	
	const formatTime =(val)=>{
		var h = 0, m = 0, s;
		val = parseInt(val, 10);

		if ( isNaN( val ) || val == '' ) {
			return '00:00';
		}

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

	const changeTrack=(index, call_play=true)=>{
		if(!state.playlist.length || !state.playlist[index]){
			return;
		}
		
		state.audio.currentTime = 0;
		state.audio.src         = state.playlist[index].podcast_url;

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

	const getList=(cb)=>{
		setState({...state, fetching: true});

		window.jQuery.ajax({
			url: window.ajaxurl || window.simple_podcasting_data.ajaxurl,
			type: 'POST',
			data: {
				post_id,
				action: 'simple_podcast_get_playlist',
			},
			success: function(resp) {
				let {playlist=[], current=0} = resp?.data;

				// Set from editor
				if ( props.podcast ) {
					playlist = playlist.filter(podcast=>podcast.post_id!=props.podcast.post_id);
					playlist.push(props.podcast);
					current = playlist.length-1
				}

				setState({
					...state,
					playlist     : [...playlist],
					active_index : current,
					fetching     : false,
				});
			}
		})
	}

	useEffect(getList, []);	
	useEffect(()=>changeTrack(state.active_index, false), [state.playlist]);

	if( state.fetching ) {
		return <div>
			Loading Podcast...
		</div>
	} else if(!state.playlist.length) {
		return <div>
			Nothing to play.
		</div>
	}

	let has_playlist = state.playlist.length > 1;
	let podcast      = state.playlist[state.active_index];

	const meta = [
		podcast.podcast_explicit && <><span class="dashicons dashicons-warning"></span> Explicit Content</> || null,
		podcast.podcast_episode_type && <span>Type #{podcast.podcast_episode_type}</span> || null,
		podcast.podcast_episode_number && <span>Episode #{podcast.podcast_episode_number}</span> || null,
		podcast.podcast_season_number && <span>Season #{podcast.podcast_season_number}</span> || null,
	].filter(m=>m);
	
	return  <div className="simple-podcasting-player">
		<div className="simple-podcast-player">
			<div className="simple-podcast-display">
				<div className="simple-podcast-album">
					<div className="simple-podcast-cover">
						{podcast.thumbnail_url && <img src={podcast.thumbnail_url}/> || <i className="dashicons dashicons-format-audio"></i>}
					</div>
					<div className="simple-podcast-info">
						<div className="simple-podcast-title">
							{podcast.podcast_title}
						</div>

						<div className="simple-podcast-artist">
							{podcast.podcast_terms.join(', ')}
						</div>

						<div className="meta-info">
							{meta.map((m, index)=>{
								return <span key={index}>
									<span className="meta-content">{m}</span>
									{index < meta.length-1 && <span className="meta-separator" style={{display: 'inline-block', margin: '0 5px', fontWeight: 'bold'}}>·</span> || null}
								</span>
							})}
						</div>
						{/* This is for editor */}
						{ setAttributes && RichText && ( ( caption && caption.length ) || !! isSelected ) && (
							<div>
								<br/>
								<RichText
									tagName="figcaption"
									placeholder={ __( 'Write caption…', 'simple-podcasting' ) }
									value={ caption }
									onChange={ ( value ) => setAttributes( { caption: value } ) }
									isSelected={ isSelected }
								/>
							</div> || null
						) }

						{/* This is for post view */}
						{!setAttributes && caption && <>
							<br/>
							<div>
								<small dangerouslySetInnerHTML={{__html: caption}}></small>
							</div>
						</> || null}
					</div>
				</div>
			</div>
			<div className="simple-podcast-controls flex-wrap flex-align">
				<div className="simple-podcast-plauseward flex flex-align">
					{has_playlist && <button type="button" className="simple-podcast-prev dashicons dashicons-controls-back" disabled={state.active_index==0} onClick={()=>changeTrack(state.active_index-1)}></button> || null}
					<button type="button" className={"simple-podcast-plause dashicons dashicons-controls-"+(state.is_playing ? 'pause' : 'play')} onClick={()=>state.audio[state.is_playing ? 'pause' : 'play']()}></button>
					{has_playlist && <button type="button" className="simple-podcast-next dashicons dashicons-controls-forward" disabled={state.active_index >= state.playlist.length - 1} onClick={()=>changeTrack(state.active_index+1)}></button> || null}
				</div>
				<div className="simple-podcast-tracker simple-podcast-load">
					<input className="simple-podcast-progress" type="range" min="0" max="100" step={.1} value={state.progress} onChange={seek}/>
					<div className="simple-podcast-buffer"></div>
				</div>
				<div className="simple-podcast-time flex flex-align">
					<span className="start-time">{state.start_time}</span>
					<span className="simple-podcast-slash">&#160;/&#160;</span>
					<span className="end-time">{state.end_time}</span>
				</div>
				<div className="simple-podcast-volume flex flex-align">
					<button type="button" className={"simple-podcast-mute dashicons dashicons-controls-volume"+(state.volume==0 ? 'off' : 'on')} onClick={e=>volumeSet(state.volume==0 ? state.previous_volume : 0, state.previous_volume)}></button>
					<input className="simple-podcast-v-slider" type="range" min="0" max="100" step={1} value={state.volume} onChange={volumeSet}/>
				</div>
				<div className="simple-podcast-others flex flex-align">
					{has_playlist && <div className="simple-podcast-shide">
						<button type="button" className={"simple-podcast-shide-bottom dashicons dashicons-arrow-"+(state.show_playlist ? 'up' : 'down')} title="Show/Hide Playlist" onClick={()=>setState({...state, show_playlist: !state.show_playlist})}></button>
					</div> || null}
				</div>
			</div>
		</div>
		{
			(state.show_playlist && has_playlist) && <div className="simple-podcast-playlist">
				<ul>
					{state.playlist.map((media, index)=>{
						let {podcast_url, podcast_title, podcast_duration} = media;
						return <li key={index} className={state.active_index==index ? "simple-podcast-active" : ''} onClick={()=>changeTrack(index)}>
							<span className="simple-podcast-source" data-src={podcast_url}>{podcast_title}</span>
							<span className="simple-podcast-desc">{podcast_duration}</span>
						</li>
					})}
				</ul>
			</div> || null
		}
	</div>
}
