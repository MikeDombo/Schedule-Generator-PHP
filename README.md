# Custom Manual Schedule Generator
Fully manual version of my schedule generator that requires the user to input all information for all sections and courses.

## What It Is
Our app accepts as many courses as a student may want to take along with all the offered sections of each course.  Then, with
our algorithm, generates all possible non-conflicting schedules.

## Where It Is
Right now it is available at http://mikedombrowski.com/sched/.

## Run Your Own
1. Clone this repository
2. Install dependencies using composer. `php composer.phar install` or `composer install`
3. Edit `config.php` and change the `SUBDIR` constant to reflect your installation location

## PHP API Documentation
Available at https://MikeDombo.github.io/Schedule-Generator-PHP/html/index.html

## Theory of Operation
The new algorithm implemented by commit 48b40fe is the Bron-Kerbosch maximal clique finding algorithm. I realized that the scheduling program could be thought of as a graph where vertices represent a section of a class and edges exist between vertices that are compatible (can be taken together).

Representing the problem as a graph means that possible non-conflicting schedules are maximal cliques. Therefore, to find all possible schedules, I implemented the Bron-Kerbosch maximal clique finding algorithm. This does run faster than my old algorithm and generates fewer total schedules because the old algorithm generated some schedules that were included in larger ones (sub-graphs). 

## Old Theory of Operation
Our algorithm is essentially a recursive tree, which is explained more in the image below.
![Theory of Operation](http://mikedombrowski.com/wp-content/uploads/2015/10/illustration.png)
