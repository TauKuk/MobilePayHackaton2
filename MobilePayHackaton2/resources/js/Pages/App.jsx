import './App.css';

import NavBar from '../Components/NavBar/NavBar';
import LandingPage from '../Components/LandingPage/LandingPage';
import Footer from '../Components/Footer/Footer';

function App() {
  return (
    <div className="App">
      <NavBar />
      <main>
        <LandingPage />
        <Footer />
      </main>

    </div>
  );
}

export default App;