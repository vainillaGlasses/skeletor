import React, { Component } from 'react';
import axios from 'axios';

import Album from './Album';

class FilterAlbums extends Component {

    constructor(props) {
        super(props);
        this.state = {
            albums: [],
            albumsInit: [],
            message: false,
    };
        this.filterAlbum = this.filterAlbum.bind(this);
    }

    componentDidMount() {
    axios.get('http://skeletor.docker/api/albums?_format=json')
        .then(res => {
            const albumsInit = res.data.albums;
            const albums = albumsInit;
            this.setState({ albumsInit });
            this.setState({ albums });
        })
    }

    filterAlbum(e) {
        var updatedList = this.state.albumsInit;
        updatedList = updatedList.filter((item =>{
            return item.title.toLowerCase().search(
                    e.target.value.toLowerCase()) !== -1;
        }) );
        this.setState({
            albums: updatedList,
        });
        if (updatedList === 0 ) {
            this.setState({
                message: true,
            });
        } else {
            this.setState({
                message: false,
            });
        }
    }

    render() {
        return (
            <div class="albumContainer">
            <div class="albumInput">
            <input type="text"
            className="center-block"
            placeholder="Filter here…"
            onChange={this.filterAlbum}
            /></div>
        {this.state.albums.map(album =>
            <div key={ Math.floor( Math.random() * 10000 ) + 1 }><Album title={album.title} cover={album.cover}/></div>
        )}
        {this.state.message ? <div>No search results.</div> : '' }

    </div>
    );
    }

}

export default FilterAlbums;
