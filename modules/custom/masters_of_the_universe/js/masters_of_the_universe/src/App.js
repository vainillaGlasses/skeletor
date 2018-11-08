import React, { Component } from 'react';

import './App.css';

import axios from 'axios';

class App extends Component {
  state = {
    albums: []
  }

  componentDidMount() {
    axios.get('http://skeletor.docker/api/albums?_format=json')
      .then(res => {
        console.log(res.data.albums);
        const albums = res.data.albums;
        this.setState({ albums });
      })
  }

  render() {
    return (
      <ul>
        { this.state.albums.map(album => <li>{album.title}</li>)}
      </ul>
    )
  }
}

export default App;
