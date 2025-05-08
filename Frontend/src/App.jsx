import React from 'react'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

import Pegawai from './pegawai/pegawai'
import CreatePegawai from './pegawai/createPegawai'
import UpdatePegawai from './pegawai/updatePegawai'
import Layout from './navbar/layout';

function App() {
  return (
    <Router>
      <Routes>
        <Route path='/' element={<Layout />}>
          <Route path="/pegawai" element={<Pegawai />} />
          <Route path="/pegawai/create" element={<CreatePegawai />} />
          <Route path="/pegawai/update/" element={<UpdatePegawai />} />
        </Route>
      </Routes>
    </Router>
  )
}

export default App
