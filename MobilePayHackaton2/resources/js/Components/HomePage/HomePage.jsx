import React from "react";

import "./HomePage.scss";
import EventData from "../../data/EventData";

export default function HomePage() {
    const currentEvents = EventData.map((event) => {
        return (
            <a className="event--tile" href={"/home/events/" + event.id}>
                <div className="event--id_name">                
                    <div className="event--id">{ event.id }. </div>
                    <div className="event--name">{ event.name }</div>
                </div>

                <div className="event--description">{ event.description }</div>
                <div className="event--type">{ event.type }</div>
                <div className="event--total_distance_km">{ event.total_distance_km }</div>
            </a>
        )
    })
    
    return (
        <>
            <div className="home--wrapper">
                <div className="home--container">
                    <h2 className="challenges--header">Dabartiniai iššūkiai</h2>
                    { currentEvents }
                    <h2 className="challenges--header">Būsimi iššūkiai</h2>

                    <h2 className="challenges--header">Praėję iššūkiai</h2>

                </div>
            </div>
        </>
    )
}