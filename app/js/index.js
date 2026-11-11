async function chargerDemandes() {
  const reponse = await fetch('http://127.0.0.1:8000/api/v1/exchange-requests?status=open');
  if (!reponse.ok) throw new Error(`Erreur HTTP ${reponse.status}`);
  const resultat = await reponse.json();
  console.log(resultat.data);
}

chargerDemandes();