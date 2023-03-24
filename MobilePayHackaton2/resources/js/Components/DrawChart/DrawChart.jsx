import React from 'react';
import Chart from 'chart.js/auto';
import "./DrawChart.scss"

import { useRef, useEffect } from 'react';

export default function DrawChart({ challengeID, usernames, distances }) {
    const graphCanvas = useRef(null);
    var canvas;

    console.log(distances);

    useEffect(() => {
        console.log(graphCanvas.current);
        canvas = graphCanvas.current.getContext('2d');
        new Chart(canvas, {
            type: 'bar',
            data: {
            labels: usernames,
            datasets: [{
                label: 'Distance (km)',
                data: distances,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
            }
        });
    }, [graphCanvas]);

    
     
    return (
        <div className='graph--container'>
            <canvas ref={graphCanvas} id={challengeID} className='chart' width={"400px"} height={"400px"}></canvas>
        </div>
    )
}
