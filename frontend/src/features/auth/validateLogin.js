import * as rules from "../../utils/validationRules.js";

export const validateLogin = (formData) => {
    const errors = {};
    const {email, password} = formData;

    if (!rules.isRequired(email)) {
        errors.email = 'Email jest wymagany!';
    } else if (!rules.isValidEmail(email)) {
        errors.email = 'Niepoprawny adres email';
    }

    if (!rules.isRequired(password)) {
        errors.password = 'Hasło jest wymagane!'
    }

    return errors;
}