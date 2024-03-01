import './autorization.scss';
import $ from "jquery";
import { FormValidate } from '../ts/FormValidate';

FormValidate.onInputPhoneValidate($("#register_phone"));
FormValidate.onValidateRepeatInputs($("#register_password_first"), $("#register_password_second"), $("#register_register"));