import React from "react";

import "./HomePage.scss";
import { usePage } from "@inertiajs/inertia-react";

export default function HomePage() {
    const { challenges } = usePage().props;
    
    var types = {
        begimas: "Running",
        dviraciai: "Cycling",
        ejimas: "Walking"
    }

    const currentChallenges = challenges.filter(challenge => {
        return !challenge.hasEnded
    })

    const pastChallenges = challenges.filter(challenge => {
        return challenge.hasEnded
    })

    const currentEvents = currentChallenges.map((event) => {
        return (
            <a className="event--tile" href={"/events/" + event.id}>
                <div className="event--id_name">                
                    {/* <div className="event--id">{ event.id }. </div> */}
                    <div className="event--name">{ event.name }</div>
                </div>

                <div className="event--description">{ event.max_score }</div>
                <div className="event--type">{ types[event.type] }</div>
                <div className="event--total_distance_km">{ event.total_distance_km }</div>
            </a>
        )
    })

    const pastEvents = pastChallenges.map((event) => {
        return (
            <a className="event--tile" href={"/events/" + event.id}>
                <div className="event--id_name">                
                    {/* <div className="event--id">{ event.id }. </div> */}
                    <div className="event--name">{ event.name }</div>
                </div>

                <div className="event--description">{ event.max_score }</div>
                <div className="event--type">{ types[event.type] }</div>
                <div className="event--total_distance_km">{ event.total_distance_km }</div>
            </a>
        )
    })
    
    
    return (
        <>
            <div className="home--wrapper">
                <div className="home--container">
                    <h2 className="challenges--header">Current challenges</h2>
                    {
                        currentEvents.size != 0 && 
                        (
                            <div className="event--tile--header">
                                <div className="event--id_name">                
                                    {/* <div className="event--id">{ event.id }. </div> */}
                                    <div className="event--name">Name</div>
                                </div>

                                <div className="event--description">Max score</div>
                                <div className="event--type">Type</div>
                                <div className="event--total_distance_km">Challenge length (km)</div>
                            </div>
                        )
                    }
                    { currentEvents }
                
                    <h2 className="challenges--header">Past challenges</h2>
                    {
                        pastEvents.size != 0 && 
                        (
                            <div className="event--tile--header">
                                <div className="event--id_name">                
                                    {/* <div className="event--id">{ event.id }. </div> */}
                                    <div className="event--name">Name</div>
                                </div>

                                <div className="event--description">Max score</div>
                                <div className="event--type">Type</div>
                                <div className="event--total_distance_km">Challenge length (km)</div>
                            </div>
                        )
                    }
                    { pastEvents }
                </div>
            </div>
        </>
    )
}