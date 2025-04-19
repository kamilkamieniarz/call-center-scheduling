import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './App.css';

function App() {
  const [agents, setAgents] = useState([]);
  const [demands, setDemands] = useState([]);
  const [schedule, setSchedule] = useState([]);
  const [loading, setLoading] = useState(false);

  const apiBaseUrl = 'https://kamilkamieniarz.pl/public/api';

  // Pobierz agentów i zapotrzebowanie
  useEffect(() => {
    axios.get(`${apiBaseUrl}/agents`)
      .then(response => setAgents(response.data))
      .catch(error => console.error('Błąd pobierania agentów:', error));

    axios.get(`${apiBaseUrl}/demands`)
      .then(response => setDemands(response.data))
      .catch(error => console.error('Błąd pobierania zapotrzebowania:', error));
  }, []);

  // Generuj harmonogram
  const generateSchedule = () => {
    setLoading(true);
    axios.post(`${apiBaseUrl}/schedule`)
      .then(response => {
        setSchedule(response.data);
        setLoading(false);
      })
      .catch(error => {
        console.error('Błąd generowania harmonogramu:', error);
        setLoading(false);
      });
  };

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-2xl font-bold mb-4">Call Center Scheduler</h1>

      <div className="mb-6">
        <h2 className="text-xl font-semibold">Agenci</h2>
        <ul className="list-disc pl-5">
          {agents.map(agent => (
            <li key={agent.id}>
              {agent.name} (Umiejętności: {agent.skills.join(', ')})
            </li>
          ))}
        </ul>
      </div>

      <div className="mb-6">
        <h2 className="text-xl font-semibold">Zapotrzebowanie</h2>
        <ul className="list-disc pl-5">
          {demands.map(demand => (
            <li key={demand.id}>
              {demand.queue} - {demand.day} {demand.hour}:00 ({demand.predictedCalls} połączeń)
            </li>
          ))}
        </ul>
      </div>

      <div className="mb-6">
        <button
          onClick={generateSchedule}
          disabled={loading}
          className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          {loading ? 'Generowanie...' : 'Generuj Harmonogram'}
        </button>
      </div>

      {schedule.length > 0 && (
        <div>
          <h2 className="text-xl font-semibold mb-2">Harmonogram</h2>
          <table className="w-full border-collapse border border-gray-300">
            <thead>
              <tr className="bg-gray-100">
                <th className="border border-gray-300 p-2">Dzień</th>
                <th className="border border-gray-300 p-2">Godzina</th>
                <th className="border border-gray-300 p-2">Kolejka</th>
                <th className="border border-gray-300 p-2">Przypisani Agenci</th>
              </tr>
            </thead>
            <tbody>
              {schedule.map((slot, index) => (
                <tr key={index}>
                  <td className="border border-gray-300 p-2">{slot.day}</td>
                  <td className="border border-gray-300 p-2">{slot.hour}:00</td>
                  <td className="border border-gray-300 p-2">{slot.queue}</td>
                  <td className="border border-gray-300 p-2">{slot.assignedAgents.join(', ')}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}

export default App;
