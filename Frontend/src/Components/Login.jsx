import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast, ToastContainer } from 'react-toastify';
import { Login } from '../Api/apiAuth'; // Assuming you have an API function for login
// import NavbarPage from './Navbar';
import './LoginCss.css';

const LoginForm = () => {
  const navigate = useNavigate();
  const [data, setData] = useState({ email: '', password: '' });
  const [errorMsg, setErrorMsg] = useState('');

  const handleChange = (e) => {
    setData({ ...data, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await Login(data);
      const user = res.user;

      // Simpan token dan data user
      sessionStorage.setItem("token", res.access_token);
      sessionStorage.setItem("user", JSON.stringify(user));

      // Simplified role checking
      if (user) {
        toast.success("Login Berhasil!");

        // Redirecting based on user role (You can update the roles accordingly)
        if (user.jabatan_pegawai) {
          switch (user.jabatan_pegawai) {
            case "Dasboard":
              navigate("/");
              break;
            case "Admin":
              navigate("/Admin");
              break;
            case "Customer Service":
              navigate("/customerService");
              break;
            case "Penitip":
              navigate("/penitp");
              break;
            default:
              toast.error("Peran tidak dikenali.");
              break;
          }
        } else {
          // If no role, default to customer page
          navigate("/customer");
        }
      } else {
        toast.error("Peran tidak dikenali.");
      }
    } catch (error) {
      console.error("Login gagal:", error);
      setErrorMsg("Email atau Password Salah!");
      toast.error("Email atau Password Salah!");
    }
  };

  return (
    <>
      {/* <NavbarPage /> */}
      <div className="container d-flex justify-content-center align-items-center vh-100">
        <div className="card shadow-sm p-4" style={{ minWidth: "350px" }}>
          <h3 className="text-center mb-3">Login</h3>
          {errorMsg && <div className="alert alert-danger">{errorMsg}</div>}
          <form onSubmit={handleSubmit}>
            <div className="mb-3">
              <label htmlFor="email" className="form-label">
                Email
              </label>
              <input
                type="email"
                className="form-control"
                id="email"
                name="email"
                value={data.email}
                onChange={handleChange}
                required
              />
            </div>
            <div className="mb-3">
              <label htmlFor="password" className="form-label">
                Password
              </label>
              <input
                type="password"
                className="form-control"
                id="password"
                name="password"
                value={data.password}
                onChange={handleChange}
                required
              />
            </div>
            <button type="submit" className="btn btn-primary w-100">
              Login
            </button>
          </form>
          <div className="text-center mt-3">
            <a href="/forgot-password" className="d-block">
              Forgot Password?
            </a>
            <span>
              Don't have an account? <a href="/register">Register</a>
            </span>
          </div>
        </div>
      </div>
    </>
  );
};

export default LoginForm;
