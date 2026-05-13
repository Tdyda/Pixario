import * as rules from "../../utils/validationRules.js";

export const validateEmail = (email) => {
    const errors = {};

    if (!rules.isRequired(email)) {
        errors.email = 'Podaj adres email!';
    } else if (!rules.isValidEmail(email)) {
        errors.email = 'Nieprawidłowy adres email!'
    } else {
        errors.email = undefined;
    }

    return errors;
}
export const validatePassword = (password) => {
    const errors = {};

    if (!rules.isRequired(password)) {
        errors.password = 'Podaj hasło!'
    } else if (!rules.hasNumber(password) || !rules.hasSpecialChar(password) || !rules.isMinLength(password, 8)) {
        errors.password = 'Hasło musi składać się co najmniej z 8 znaków i przynajmniej jednej cyfry oraz jednego znaku specjalnego!'
    } else {
        errors.password = undefined
    }

    return errors;
}
export const validateRetypePassword = (password, retypedPassword) => {
    const errors = {};

    if (!rules.isRequired(retypedPassword)) {
        errors.retypedPassword = 'Podaj hasło jeszcze raz!'
    } else if (!rules.matchPasswords(password, retypedPassword)) {
        errors.retypedPassword = 'Podane hasła nie zgadzają się ze sobą!';
    } else {
        errors.retypedPassword = undefined;
    }

    return errors;
}

export const validateRegister = (formData) => {
    const {email, password, retypedPassword} = formData;

    return {
        ...validateEmail(email),
        ...validatePassword(password),
        ...validateRetypePassword(password, retypedPassword)
    }
}