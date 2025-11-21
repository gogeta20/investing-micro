USE investing_db;

INSERT INTO stocks (uid, symbol, name, sector, currency)
VALUES
(UUID(), 'AAPL',  'Apple Inc.',              'Technology', 'USD'),
(UUID(), 'MSFT',  'Microsoft Corp.',         'Technology', 'USD'),
(UUID(), 'GOOGL', 'Alphabet Inc. (Google)',  'Technology', 'USD'),
(UUID(), 'AMZN',  'Amazon.com Inc.',         'Consumer/Tech', 'USD'),
(UUID(), 'META',  'Meta Platforms Inc.',     'Technology', 'USD'),

(UUID(), 'NVDA',  'Nvidia Corp.',            'Semiconductors/AI', 'USD'),
(UUID(), 'PLTR',  'Palantir Technologies',   'Data/AI', 'USD'),
(UUID(), 'AI',    'C3.ai Inc.',              'Enterprise AI', 'USD'),
(UUID(), 'LMND',  'Lemonade Inc.',           'Insurtech/AI', 'USD'),
(UUID(), 'PATH',  'UiPath Inc.',             'Automation/AI', 'USD'),

-- (UUID(), 'ORCL',  'Oracle Corp.',            'Enterprise Software', 'USD'),
(UUID(), 'AMPX',  'Amprius Technologies',    'Batteries/Energy Tech', 'USD');
