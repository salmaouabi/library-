const express = require('express');
const path = require('path');
const mongoose = require('mongoose');
const authRoutes = require('./routes/auth');

const app = express();

app.use(express.json()); // Middleware pour parser JSON
app.use('/auth', authRoutes);

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

module.exports = app;