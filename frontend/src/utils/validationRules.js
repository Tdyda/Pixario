export const isRequired = (value) => {
    return value?.trim() !== '';
}

export const isValidEmail = (email) => {
    return /\S+@\S+\.\S+/.test(email);
}

export const isMinLength = (value, minLength) => {
    return typeof value === 'string' && value.length >= minLength;
}

export const hasNumber = (value) => {
    return /\d/.test(value);
}

export const hasSpecialChar = (value) => {
    return /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>?]/.test(value);
};

export const matchPasswords = (password, retypedPassword) => {
    return password === retypedPassword;
}