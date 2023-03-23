import React from 'react';

import NavBarAuth from '../Components/NavBarAuth/NavBarAuth';
import Footer from '../Components/Footer/Footer';
import EventCreateContent from '../Components/EventCreateContent/EventCreateContent';

function EventCreate() {
  return (
    <div className="App">
            <main>        
                <NavBarAuth />
                <EventCreateContent />
            </main>
        <Footer />
    </div>
  );
}

export default EventCreate;