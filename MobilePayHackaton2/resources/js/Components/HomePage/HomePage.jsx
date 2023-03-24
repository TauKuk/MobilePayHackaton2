import React from "react";

import "./HomePage.scss";
import { usePage } from "@inertiajs/inertia-react";

export default function HomePage() {
    const { challenges } = usePage().props;
    
    var types = {
        begimas: "Bėgimas",
        dviraciai: "Dviračiai",
        ejimas: "Ėjimas"
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
                    <h2 className="challenges--header">Dabartiniai iššūkiai</h2>
                    {
                        currentEvents.size != 0&& 
                        (
                            <div className="event--tile--header">
                                <div className="event--id_name">                
                                    {/* <div className="event--id">{ event.id }. </div> */}
                                    <div className="event--name">Pavadinimas</div>
                                </div>

                                <div className="event--description">Max taškai</div>
                                <div className="event--type">Tipas</div>
                                <div className="event--total_distance_km">Visa distancija (km)</div>
                            </div>
                        )
                    }
                    { currentEvents }
                
                    <h2 className="challenges--header">Praėję iššūkiai</h2>
                    {
                        pastEvents.size != 0 && 
                        (
                            <div className="event--tile--header">
                                <div className="event--id_name">                
                                    {/* <div className="event--id">{ event.id }. </div> */}
                                    <div className="event--name">Pavadinimas</div>
                                </div>

                                <div className="event--description">Max taškai</div>
                                <div className="event--type">Tipas</div>
                                <div className="event--total_distance_km">Visa distancija (km)</div>
                            </div>
                        )
                    }
                    { pastEvents }
                </div>
            </div>
        </>
    )
}