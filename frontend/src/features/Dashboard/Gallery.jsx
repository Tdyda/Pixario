import React from 'react';

export default function Gallery({ title, images }) {
    const imgArray = Array(images).fill('https://via.placeholder.com/150');

    return (
        <div className="mt-4">
            <h5>{title}</h5>
            <div className="row g-2">
                {imgArray.map((src, idx) => (
                    <div className="col-4" key={idx}>
                        <img src={src} alt="Gallery" className="img-fluid rounded" style={{ aspectRatio: '1 / 1', objectFit: 'cover' }} />
                    </div>
                ))}
            </div>
        </div>
    );
}