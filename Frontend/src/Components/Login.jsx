import React from 'react';
import './LoginCss.css';

const LoginForm = () => {
  return (
    <div className="container d-flex justify-content-center align-items-center vh-100">
      <div className="card shadow-sm">
        <h3 className="text-center mb-3">Login</h3>
        <form>
          <div className="mb-3">
            <label htmlFor="username" className="form-label">Username</label>
            <input type="text" className="form-control" id="username" placeholder="Enter username" />
          </div>
          <div className="mb-3">
            <label htmlFor="password" className="form-label">Password</label>
            <input type="password" className="form-control" id="password" placeholder="Enter password" />
          </div>
          <div className="d-flex justify-content-between align-items-center mb-3">
            <a href="#" className="text-decoration-none">Forgot Password?</a>
          </div>
          <button type="submit" className="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  );
};

export default LoginForm;
