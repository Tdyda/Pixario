function FormInput({ label, type, name, value, disabled, onChange }) {
    return (
        <div className="mb-3">
            <label className="form-label">{label}</label>

            <input
                type={type}
                name={name}
                className="form-control"
                value={value}
                onChange={onChange}
                disabled={disabled}
            />
        </div>
    );
}

export default FormInput;