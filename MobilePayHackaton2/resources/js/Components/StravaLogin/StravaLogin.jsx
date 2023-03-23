import React from 'react';

export default function StravaLogin() {
  const clientId = "104420";
  const redirectUri = "http://localhost:3000";
  const responseType = 'code';
  const scope = 'read,activity:read_all';

  const authUrl = `http://www.strava.com/oauth/authorize?client_id=${clientId}&response_type=${responseType}&redirect_uri=${redirectUri}/exchange_token&approval_prompt=force&scope=${scope}`;

  return (
      <a href={authUrl}>Prisijungti</a>
  );
};