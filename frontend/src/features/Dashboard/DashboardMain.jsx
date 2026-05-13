import React from 'react';
import SessionCard from './SessionCard';
import Gallery from './Gallery';

export default function DashboardMain() {
    return (
        <main className="col-md-10 p-4">
            <h2>Welcome, David</h2>

            <div className="row mt-4">
                <div className="col-md-6">
                    <div className="card p-3 mb-4">
                        <h5>Upcoming Sessions</h5>
                        <SessionCard date="Apr 25, 2024" time="10:00 AM" client="Emma Johnson" />
                        <SessionCard date="Apr 27, 2024" time="02:00 PM" client="Michael Smith" />
                        <SessionCard date="May 3, 2024" time="11:00 AM" client="Olivia Brown" />
                    </div>

                    <div>
                        <h5>Clients</h5>
                        <div className="card p-2 mb-2">Emma Johnson</div>
                        <div className="card p-2 mb-2">Michael Smith</div>
                    </div>

                    <Gallery title="Gallery" images={4} />
                </div>

                <div className="col-md-6">
                    <div className="card p-3 mb-4">
                        <h5>Client Gallery</h5>
                        <p className="mb-1">Sophia’s Gallery</p>
                        <button className="btn btn-secondary">Enter Gallery</button>
                    </div>

                    <Gallery title="GI/lent Gallery" images={5} />
                </div>
            </div>
        </main>
    );
}