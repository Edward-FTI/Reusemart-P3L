import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
// import './index.css'
import App from './App.jsx'
import Login from './Components/Login.jsx'
import Pegawai from './pegawai/pegawai.jsx'
import Layout from './navbar/layout.jsx'

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
