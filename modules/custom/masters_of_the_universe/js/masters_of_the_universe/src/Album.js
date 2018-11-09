import React, { Component } from 'react';

import './Album.css';

class Album extends Component {

    constructor() {
        super();
        this.baseUrl = 'http://skeletor.docker';
    }

    render() {
        return (
        <div class="album">
            <div><img src={this.baseUrl + this.props.cover} width="200" height="200"></img></div>
            <div>{this.props.title}</div>
        </div>
    );
    }
}

export default Album;