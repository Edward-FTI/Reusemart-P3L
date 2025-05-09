import { Button, Alert, Form } from "react-bootstrap";
import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import InputFloatingForm from "./InputFloatingForm";
import { SignUp } from "../../api/apiAuth";
import LoginForm from "../Login";

const FormRegister = () => {
  const navigate = useNavigate();
  const [isDisabled, setIsDisabled] = useState(true);
  const [data, setData] = useState({
    nama: "",
    email: "",
    no_hp: "",
    password: "",
  });

  const handleChange = (event) => {
    setData({ ...data, [event.target.name]: event.target.value });
  };

  const handleCheck = (e) => {
    let isChecked = e.target.checked;
    setIsDisabled(!isChecked);
  };

  const Register = (event) => {
    event.preventDefault();
    SignUp(data)
      .then((res) => {
        navigate("/");
        toast.success(res.message);
      })
      .catch((err) => {
        console.log(err);
        toast.error(err.message);
      });
  };

  return (
    <Form
      style={{ maxWidth: "600px", margin: "auto" }}
      onSubmit={Register}
      className="p-4 rounded formContainer"
    >
      <Alert variant="primary" className="mb-4 alertColor text-center">
        <strong>Info!</strong> Semua form wajib diisi.
      </Alert>

      <div className="d-flex flex-column gap-3"> {/* <<< INI KUNCI SPASI */}
        <InputFloatingForm
          type="text"
          name="nama"
          onChange={handleChange}
          placeholder="Masukkan Nama"
        />
        <InputFloatingForm
          type="email"
          name="email"
          onChange={handleChange}
          placeholder="Masukkan Email"
        />
        <InputFloatingForm
          type="text"
          name="no_hp"
          onChange={handleChange}
          placeholder="Masukkan No HP"
        />
        <InputFloatingForm
          type="password"
          name="password"
          onChange={handleChange}
          placeholder="Masukkan Password"
          autoComplete="off"
        />
      </div>

      <label className="d-flex justify-content-start align-items-center mt-4">
        <Form.Check type="checkbox" onChange={handleCheck} />
        <p className="ms-2 mb-0">
          Have you Already Read the{" "}
          <a href="https://www.youtube.com/static?template=terms&gl=ID">
            Terms of Service
          </a>
        </p>
      </label>

      <Button
        disabled={isDisabled}
        type="submit"
        className="mt-3 w-100 border-0 buttonSubmit btn-lg"
      >
        Register
      </Button>

      <p className="text-end mt-2">
        Already Have an Account? <Link to="/LoginForm">Click Here!</Link>
      </p>
    </Form>
  );
};

export default FormRegister;
