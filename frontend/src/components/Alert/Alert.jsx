import {useState, useEffect} from "react";

const Alert = ({message, onClose, alertType}) => {
    const [isPopupActive, setIsPopupActive] = useState(false)
    const [colors] = useState({success: '#ddffdd', warning: '#ffffcc', error: '#ffdddd'})

    useEffect(() => {
        let fadeoutTimer, remove;
        setTimeout(() => setIsPopupActive(true), 10);

        fadeoutTimer = setTimeout(() => {
            setIsPopupActive(false);
        }, 3000);

        remove = setTimeout(() => {
            onClose();
        }, 3500);

        return () => {
            clearTimeout(fadeoutTimer);
            clearTimeout(remove);
        }
    }, [alertType, onClose]);

    const backgroundColor = colors[alertType];

    return (
        <div style={{width: '100%'}}>
            <div style={{
                width: '100%',
                height: '60px',
                opacity: isPopupActive ? 1 : 0,
                visibility: isPopupActive ? 'visible' : 'hidden',
                transition: '1s ease-in-out',
                position: 'fixed',
                top: 0,
                left: 0,
                backgroundColor,
                zIndex: 9999,
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center'
            }}
            >
                <p className={'m-0'}>{message}</p>
            </div>
        </div>
    )
}

export default Alert;