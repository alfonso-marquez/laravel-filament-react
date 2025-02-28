import CardsList from "./components/cards-list/cards-list";
import Header from "./components/header/header";

function App() {
    return (
        <div>
            <Header />
            <main>
                <h2>Time to get started!</h2>
                <CardsList />
            </main>
        </div>
    );
}

export default App;
