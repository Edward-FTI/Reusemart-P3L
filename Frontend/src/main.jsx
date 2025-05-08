import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
// import './index.css'
import App from './App.jsx'
import LoginForm from './Components/Login.jsx'
import NavbarPage from './Components/Navbar.jsx'
import Footer from './Components/Footer.jsx'
import Dasboard from './Components/Dasboard.jsx'
import Admin from './Admin/Admin.jsx'
import Pegawai from './Pegawai/Pegawai.jsx'
import Customer from './Customers/Customer.jsx'

createRoot(document.getElementById('root')).render(
  <>
    {/* <NavbarPage /> */}
    {/* <Dasboard /> */}
    {/* <Footer /> */}
    <LoginForm />
  </>,
)
