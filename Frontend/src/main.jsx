import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
// import './index.css'
import AppRouter from './Routes/Routes.jsx'
import App from './App.jsx'
import LoginForm from './Components/Login.jsx'
import NavbarPage from './Components/Navbar.jsx'
import Footer from './Components/Footer.jsx'
import Admin from './Admin/Admin.jsx'

createRoot(document.getElementById('root')).render(
  <>
    {/* <NavbarPage /> */}
    {/* <Footer /> */}
    {/* <LoginForm /> */}
    <AppRouter />
  </>,
)
