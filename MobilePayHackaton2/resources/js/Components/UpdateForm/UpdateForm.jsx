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
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('updateEvent'));
    };

    return (
        <>
            <div className="create--title">Redaguoti iššūkį</div>        
            <form onSubmit={submit} className="create--form">                                
            <div className="challenge--input--container">
                <label htmlFor="name">Pavadinimnas</label>
                <input type="text" className="challenge--input" id="name" value={data.name} onChange={e => {setData('name', e.target.value)}} autoComplete="false"/>
                { errors.name ? <div className="edit--error"> {errors.name} </div> : ""}
            </div>

            <div className="challenge--input--container">
                <label htmlFor="description">Apibūdinimas</label>
                <input type="text" className="challenge--input" id="description" value={data.description} onChange={e => {setData('description', e.target.value)}} autoComplete="false"/>
                <div className="edit--error">{ errors.description }</div>
            </div>

            <div className="challenge--input--container">
                <label htmlFor="max_score">Maksimalus taškų kiekis</label>
                <input type="number" className="challenge--input" id="max_score" value={data.max_score} onChange={e => {setData('max_score', e.target.value)}} autoComplete="false"/>
                <div className="edit--error">{ errors.max_score }</div>
            </div>

            <div className="challenge--input--container">
                <label htmlFor="type">Tipas</label>
                <select name="type" id="type" className="challenge--input" value={data.type} onChange={e => {setData('type', e.target.value)}}>
                    <option value="null"></option>
                    <option value="begimas">Bėgimas</option>
                    <option value="dviraciai">Dviračiai</option>
                    <option value="ejimas">Ėjimas</option>
                </select>
                <div className="edit--error">{ errors.type }</div>
            </div>

            <div className="challenge--input--container">
                <label htmlFor="total_distance_km">Iššūkio atstumas (km)</label>
                <input type="number" className="challenge--input" id="total_distance_km" value={data.total_distance_km} onChange={e => {setData('total_distance_km', e.target.value)}} autoComplete="false"/>
                <div className="edit--error">{ errors.total_distance_km }</div>
            </div>

            <button className="create--button" type="submit" disabled={processing}>Redaguoti iššūkį</button>
        </form>
        </>

    )
}