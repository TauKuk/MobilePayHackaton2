import React from "react";

import "./EventViewContent.scss"
import { usePage } from "@inertiajs/inertia-react";

export default function EventViewContent() {
    var { challenge, stravaID } = usePage().props;

    challenge = challenge[0];
    var types = {
        begimas: "Running",
        dviraciai: "Cycling",
        ejimas: "Walking"
    }

    return (
        <>
           <div className="event--wrapper">
                <div className="event--container">
                    <div className="event--info">
                        <div className="event--basic--info">
                            <h2 className="event--title">{challenge.name}</h2>
                            <div className="event--type">Type: {types[challenge.type]}</div>
                            <div className="event--track--size">Challenge length: {challenge.total_distance_km} km</div>
                            <div className="event--max--score">Max score: {challenge.max_score}</div>
                        </div>

                        {challenge.description ? <div className="event--description">Description: {challenge.description}</div> : ""}
                    </div>

                    {
                        (challenge.stravaID != stravaID && !challenge.hasEnded )                  
                            &&
                        (
                            <div className="event--buttons">
                                <a href={"/event/" + challenge.id + '/join'}>Log in</a>
                            </div>
                        )
                    }

                    {
                        challenge.stravaID == stravaID                   
                            &&
                        (
                            <div className="event--buttons">
                                <a href={"/event/delete/" + challenge.id}>Delete</a>
                                <a href={"/event/update/" + challenge.id}>Edit</a>
                            </div>
                        )
                    }

                </div>
            </div>
        </>
    )
}