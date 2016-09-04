using System;
using System.Collections.Generic;
using System.Data.SQLite;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;

namespace SteamVacTrack
{
    class Program
    {
        static string SQLiteDBFileName = "VACUpdate.sqlite";
        static SQLiteConnection DBConnection;
        public class TrackedUser
        {
            public string SteamID { get; set; }
            public string AddedBy { get; set; }
            public bool Banned { get; set; }
            public TrackedUser(string steamid, string addedBy, bool banned)
            {
                SteamID = steamid;
                AddedBy = addedBy;
                Banned = banned;
            }
        }

        static void Main(string[] args)
        {
            Init();
            foreach (var user in GetTrackedUsers())
            {
                if (!user.Banned)
                {
                    var isbanned = IsVacBanned(user.SteamID);
                    Console.WriteLine($"User {user.SteamID}, Vac Status: {isbanned}");
                    if (isbanned)
                    {
                        DeleteTrackedUser(user.SteamID);
                        AddTrackedUser(user.SteamID, user.AddedBy, true);
                    }
                }
            }
            DBConnection.Close();
        }
        

        static void Init()
        {
            if (!File.Exists(SQLiteDBFileName))
            {
                SQLiteConnection.CreateFile(SQLiteDBFileName);
                DBConnection = new SQLiteConnection($"Data Source={SQLiteDBFileName};Version=3;");
                DBConnection.Open();

                string StartSQL = "CREATE TABLE trackedusers (steamid INTEGER, addedby INTEGER, banned BOOLEAN)";
                SQLiteCommand command = new SQLiteCommand(StartSQL, DBConnection);
                command.ExecuteNonQuery();

                StartSQL = "CREATE TABLE users (steamid INTEGER, sessionid TEXT)";
                command = new SQLiteCommand(StartSQL, DBConnection);
                command.ExecuteNonQuery();

                DBConnection.Close();
            }
            DBConnection = new SQLiteConnection($"Data Source={SQLiteDBFileName};Version=3;");
            DBConnection.Open();
        }

        static bool IsVacBanned(string steamID)
        {
            var wc = new WebClient();

            string ProfileLink = "http://steamcommunity.com/";
            ProfileLink += (steamID.StartsWith("765")) ? "profiles/" : "id/";
            ProfileLink += steamID + "?xml=1";

            var ProfileData = wc.DownloadString(ProfileLink);
            return !ProfileData.Contains("<vacBanned>0</vacBanned>");
        }

        static List<TrackedUser> GetTrackedUsers()
        {
            var ToReturn = new List<TrackedUser>();
            string sql = "select * from trackedusers where banned = 0";
            SQLiteCommand command = new SQLiteCommand(sql, DBConnection);
            SQLiteDataReader reader = command.ExecuteReader();
            while (reader.Read())
            {
                var NewUser = new TrackedUser(reader["steamid"].ToString(), reader["addedby"].ToString(), bool.Parse(reader["banned"].ToString()));
                ToReturn.Add(NewUser);
            }
            return ToReturn;
        }

        static void AddTrackedUser(string SteamIDToTrack, string SteamIDRequestedTrack, bool banned = false)
        {
            var boolvals = $"{banned.ToString().ToLower()}".Replace("true", "1").Replace("false", "0");
            string sql = $"insert into trackedusers (steamid, addedby, banned) values ({SteamIDToTrack}, {SteamIDRequestedTrack}, {boolvals})";
            SQLiteCommand command = new SQLiteCommand(sql, DBConnection);
            command.ExecuteNonQuery();
        }
        static void DeleteTrackedUser(string SteamIDToTrack)
        {
            string sql = $"delete from trackedusers where steamid = {SteamIDToTrack}";
            SQLiteCommand command = new SQLiteCommand(sql, DBConnection);
            command.ExecuteNonQuery();
        }
    }
}
