import React from 'react';

import NavBarAuth from '../Components/NavBarAuth/NavBarAuth';
import Footer from '../Components/Footer/Footer';
import "./AskDelete.scss"
import { usePage } from '@inertiajs/inertia-react';
import { useForm } from '@inertiajs/inertia-react';

function Event() {
    var { challenge } = usePage().props;
    challenge = challenge[0];

    const { data, setData, post, processing, errors, reset } = useForm({
        id: challenge.id,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route("trueDelete"), data);
    }

    return (
        <div className="App">
                <main>        
                    <NavBarAuth />
                    <div className='delete--confirmation'>
                        <h2 className='delete--title'>Are you sure you want to delete the challenge?</h2>
                        
                        <form onSubmit={submit}>
                            <button className="event--button" type="submit">Delete</button>    
                        </form>
                    </div>
                </main>
            <Footer />
        </div>
    );
}

export default Event;