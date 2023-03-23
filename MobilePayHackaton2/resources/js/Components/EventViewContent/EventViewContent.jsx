import React from "react";

import "./EventViewContent.scss"
import { usePage } from "@inertiajs/inertia-react";

export default function EventViewContent() {
    var { challenge } = usePage().props;

    challenge = challenge[0];
    return (
        <>
           <div className="event--wrapper">
                <div className="event--container">
                    <h2>{challenge.name}</h2>
                </div>
            </div>
        </>
    )
}