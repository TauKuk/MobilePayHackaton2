import React from "react";

import { useForm } from "@inertiajs/inertia-react";
import "./UpdateForm.scss"
import { usePage } from "@inertiajs/inertia-react";

export default function UpdateForm() {
    var { challenge } = usePage().props;

    challenge = challenge[0];

    const { data, setData, post, processing, errors, reset } = useForm({
        name: challenge.name,
        description: ( challenge.description ? challenge.description : "" ),
        max_score: challenge.max_score,
        type: challenge.type,
        total_distance_km: challenge.total_distance_km,
        id: challenge.id,
        hasEnded: challenge.hasEnded,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('updateEvent'));
    };

    return (
        <>
            <div className="create--title">Edit challenge</div>        
            <form onSubmit={submit} className="create--form">                                
            <div className="challenge--input--container">
                <label htmlFor="name">Name</label>
                <input type="text" className="challenge--input" id="name" value={data.name} onChange={e => {setData('name', e.target.value)}} autoComplete="false"/>
                { errors.name ? <div className="edit--error"> {errors.name} </div> : ""}
            </div>

            <div className="challenge--input--container">
                <label htmlFor="description">Description</label>
                <input type="text" className="challenge--input" id="description" value={data.description} onChange={e => {setData('description', e.target.value)}} autoComplete="false"/>
                <div className="edit--error">{ errors.description }</div>
            </div>

            <div className="challenge--input--container">
                <label htmlFor="max_score">Max score</label>
                <input type="number" className="challenge--input" id="max_score" value={data.max_score} onChange={e => {setData('max_score', e.target.value)}} autoComplete="false"/>
                <div className="edit--error">{ errors.max_score }</div>
            </div>

            <div className="challenge--input--container">
                <label htmlFor="type">Type</label>
                <select name="type" id="type" className="challenge--input" value={data.type} onChange={e => {setData('type', e.target.value)}}>
                    <option value="null"></option>
                    <option value="begimas">Running</option>
                    <option value="dviraciai">Cycling</option>
                    <option value="ejimas">Walking</option>
                </select>
                <div className="edit--error">{ errors.type }</div>
            </div>

            <div className="challenge--input--container">
                <label htmlFor="total_distance_km">Length of challenge (km)</label>
                <input type="number" className="challenge--input" id="total_distance_km" value={data.total_distance_km} onChange={e => {setData('total_distance_km', e.target.value)}} autoComplete="false"/>
                <div className="edit--error">{ errors.total_distance_km }</div>
            </div>

            {
                !challenge.hasEnded
                &&
                (
                    <div className="challenge--input--container challenge--input--checkbox">
                        <label htmlFor="hasEnded">Has ended?</label>
                        <input type="checkbox" className="challenge--input" id="hasEnded"checked={ data.hasEnded } onChange={e => setData('hasEnded', e.target.checked)}/>
                        <div className="edit--error">{ errors.hasEnded }</div>
                    </div>
                )
            }

            <button className="create--button" type="submit" disabled={processing}>Edit challenge</button>
        </form>
        </>

    )
}