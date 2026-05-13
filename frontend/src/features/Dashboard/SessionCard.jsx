import React from 'react';

export default function SessionCard({ date, time, client }) {
    return (
        <div className="card mt-2 p-2">
            {date} — {time} — {client}
        </div>
    );
}